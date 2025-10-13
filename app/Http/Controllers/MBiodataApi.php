<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\MBiodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MBiodataApi extends Controller
{
    use ApiResponseTrait;

    // READ (Pagination) - GET /api/m-biodata
    public function index(Request $request)
    {
        $page = $request->input('page', 0) + 1;
        $size = $request->input('size', 10);
        $size = max(1, (int) $size);
        $sort = $request->input('sort', []);
        $filter = $request->input('filter', []);
        $search = $request->input('search');

        // INITIALIZE QUERY
        $query = MBiodata::query();

        // GLOBAL SEARCH
        if ($search) {
            $query->where('fullname', 'like', '%'.$search.'%');
            $query->where('mobile_phone', 'like', '%'.$search.'%');
            $query->where('image_path', 'like', '%'.$search.'%');
        }

        // SORTING
        if (is_string($sort)) {
            $sort = json_decode($sort, true);
        }
        if (! empty($sort) && is_array($sort) && isset($sort[0])) {
            $sort = $sort[0];
            if (isset($sort['id'])) {
                $sortDirection = $sort['desc'] ? 'desc' : 'asc';
                $query->orderBy($sort['id'], $sortDirection);
            }
        }

        // FILTERING
        if (is_string($filter)) {
            $filter = json_decode($filter, true);
        }
        if (! empty($filter) && is_array($filter)) {
            for ($i = 0; $i < count($filter); $i++) {

                $filterKey = $filter[$i]['id'];
                $filterValue = $filter[$i]['value'];
                $filterOperation = $filter[$i]['matchMode'];

                switch ($filterOperation) {
                    case 'EQUALS':
                        $query->where($filterKey, '=', $filterValue);
                        break;
                    case 'CONTAINS':
                        $query->where($filterKey, 'like', '%'.$filterValue.'%');
                        break;
                    default:
                        break;
                }
            }
        }

        // PAGINATING
        $roles = $query->paginate(
            $size,
            ['*'], // Columns to select
            'page', // Name of the page query parameter (it's 'page' by default)
            $page  // The current page number
        );

        // RESPONSE
        $response = [
            'totalOfPages' => $roles->lastPage(),
            'totalOfElements' => $roles->total(),
            'content' => $roles->items(),
        ];

        return $this->successResponse($response);
    }

    // READ (One) - GET /api/m-biodata/{id}
    public function show(string $id)
    {
        $mBiodata = MBiodata::where('id', $id)->firstOrFail();

        return $this->successResponse($mBiodata);
    }

    // CREATE - POST /api/m-biodata
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'mobile_phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(new ValidationException($validator));
        }

        $mBiodata = MBiodata::create([
            'fullname' => $request->fullname,
            'mobile_phone' => $request->mobile_phone,

            'created_by' => Auth::id() ?? 1, // Assuming user ID 1 for unauthenticated
            'created_on' => now(),
        ]);

        return $this->successResponse($mBiodata, 'success', 201);
    }

    // UPDATE - PUT/PATCH /api/m-biodata/{id}
    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'mobile_phone' => 'required|string|max:15',

            'is_delete' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(new ValidationException($validator));
        }

        $mBiodata = MBiodata::where('id', $id)->firstOrFail();

        $mBiodata->update([
            'fullname' => $request->fullname,
            'mobile_phone' => $request->mobile_phone,

            'modified_by' => Auth::id() ?? 1, // Track modifier
            'modified_on' => now(),
        ]);

        if ($request->is_delete != null) {
            if ($request->is_delete) {
                $mBiodata->update([
                    'deleted_by' => Auth::id() ?? 1, // Track deleter
                    'deleted_on' => now(),
                    'is_delete' => true,
                ]);
            } else {
                $mBiodata->update([
                    'is_delete' => false,
                ]);
            }
        }

        return $this->successResponse($mBiodata);
    }

    // DELETE - DELETE /api/m-biodata/{id}
    public function destroy(string $id)
    {
        $mBiodata = MBiodata::where('id', $id)->firstOrFail();

        $mBiodata->delete();

        return $this->successResponse(null, 'success', 200);
    }
}
