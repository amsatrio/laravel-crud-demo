<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\MMenu;
use Exception;
use FilesystemIterator;
use Illuminate\Support\Facades\Auth;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use UnexpectedValueException;

class MMenuController extends Controller
{
    use ApiResponseTrait;

    public function slug($slug, $action)
    {
        if ($slug == '') {
            return view('errors.404');
        }
        if ($action == '') {
            $action = 'index';
        }

        $m_menu = MMenu::where('name', $slug)->where('actions', $slug)->first();
        if ($m_menu == null) {
            return view('errors.404');
        }

        $filepath = "../resources/views/$slug/$action.blade.php";
        $this->write_file($filepath, $m_menu->code);

        return view("$slug".'.'."$action");
    }

    private function write_file($filepath, $content)
    {
        try {
            unlink($filepath);
        } catch (Exception $error) {
            echo "Error deleting file '$filepath'.";
        }

        $file_handle = fopen($filepath, 'w');
        if ($file_handle) {
            fwrite($file_handle, $content);
            fclose($file_handle);
            echo "File '$filepath' created successfully.";
        } else {
            echo "Error creating file '$filepath'.";
        }
    }

    public function debug()
    {
        $m_menus = MMenu::all();
        if ($m_menus == null) {
            return $this->errorResponse('data not found', 404, []);
        }
        for ($i = 0; $i < count($m_menus); $i++) {
            $slug = $m_menus[$i]['name'];
            $action = $m_menus[$i]['actions'];
            $code = $m_menus[$i]['code'];

            $filepath = "../resources/views/$slug/$action.blade.php";
            $this->write_file($filepath, $code);
        }

        return $this->successResponse(null);
    }

    public function debug_update()
    {
        $baseDir = '../resources/views';

        if (! is_dir($baseDir)) {
            return $this->errorResponse("Directory not found at $baseDir", 404, []);
        }

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($baseDir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.') !== false) {

                    $filePath = $file->getRealPath();

                    $relativePath = substr($filePath, strlen(realpath($baseDir)) + 1);

                    $pathParts = explode(DIRECTORY_SEPARATOR, str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath));

                    if (count($pathParts) >= 2) {
                        $slug = $pathParts[0];

                        $fileName = end($pathParts);

                        $action = str_replace('.blade.php', '', $fileName);

                        $content = file_get_contents($filePath);

                        // get data from db
                        $m_menu = MMenu::where('name', $slug)->where('actions', $slug)->first();
                        if ($m_menu != null) {
                            // update data
                            $m_menu->update([
                                'name' => $slug,
                                'actions' => $action,
                                'code' => $content,
                                'modified_by' => Auth::id() ?? 1,
                                'modified_on' => now(),
                            ]);

                            continue;
                        }

                        // insert data
                        $m_menu = MMenu::create([
                            'name' => $slug,
                            'actions' => $action,
                            'code' => $content,
                            'created_by' => Auth::id() ?? 1,
                            'created_on' => now(),
                        ]);
                    }
                }
            }

        } catch (UnexpectedValueException $e) {
            return $this->errorResponse('Error processing directory: '.$e->getMessage(), 500, []);
        }

        return $this->successResponse(null);
    }
}
