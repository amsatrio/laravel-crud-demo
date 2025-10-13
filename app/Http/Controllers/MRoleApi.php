<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\MRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MRoleApi extends Controller
{
    use ApiResponseTrait;

    // READ (All) - GET /api/m-role
    public function index()
    {
        //   $roles = MRole::where('is_delete', false)->get();
        $roles = MRole::all();
        return $this->successResponse($roles);
    }

    // READ (One) - GET /api/m-role/{id}
    public function show(string $id)
    {
        $mRole = MRole::where("id", $id)->firstOrFail();
        return $this->successResponse($mRole);
    }

    // CREATE - POST /api/m-role
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'code' => 'required|string|max:20|unique:m_role,code',
            'level' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(new ValidationException($validator));
        }

        $mRole = MRole::create([
            'name' => $request->name,
            'code' => $request->code,
            'level' => $request->level,
            'created_by' => Auth::id() ?? 1, // Assuming user ID 1 for unauthenticated
            'created_on' => now(),
        ]);

        return $this->successResponse($mRole, "success", 201);
    }

    // UPDATE - PUT/PATCH /api/m-role/{id}
    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'code' => 'required|string|max:20|unique:m_role,code,'.$id.',id',
            'level' => 'nullable|integer',
            'is_delete' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(new ValidationException($validator));
        }

        $mRole = MRole::where("id", $id)->firstOrFail();

        $mRole->update([
            'name' => $request->name,
            'code' => $request->code,
            'level' => $request->level,
            'modified_by' => Auth::id() ?? 1, // Track modifier
            'modified_on' => now(),
        ]);

        if ($request->is_delete != null) {
            if ($request->is_delete) {
                $mRole->update([
                    'deleted_by' => Auth::id() ?? 1, // Track deleter
                    'deleted_on' => now(),
                    'is_delete' => true,
                ]);
            } else {
                $mRole->update([
                    'is_delete' => false,
                ]);
            }
        }

        return $this->successResponse($mRole);
    }

    // DELETE - DELETE /api/m-role/{id}
    public function destroy(string $id)
    {
        $mRole = MRole::where("id", $id)->firstOrFail();
        $mRole->delete();

        return $this->successResponse(null, "success", 200);
    }
}
