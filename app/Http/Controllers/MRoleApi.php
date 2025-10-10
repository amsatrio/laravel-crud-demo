<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\MRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MRoleApi extends Controller
{
    use ApiResponseTrait;

  // READ (All) - GET /api/roles
  public function index(){
      Log::info("index()");
      $roles = MRole::where('is_delete', false)->get(); 
      return $this->successResponse($roles);
  }

  // READ (One) - GET /api/roles/{mRole}
  public function show(MRole $mRole){
    Log::info("show()");
      if ($mRole->IsDelete) {
          throw new NotFoundHttpException();
      }
      return $this->successResponse($mRole);
  }

  // CREATE - POST /api/roles
  public function store(Request $request){
    Log::info("store()");
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:20',
          'code' => 'required|string|max:20|unique:m_roles,code',
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

      return $this->successResponse($mRole, 201);
  }

  // UPDATE - PUT/PATCH /api/roles/{mRole}
  public function update(Request $request, MRole $mRole){
    Log::info("update()");
      if ($mRole->IsDelete) {
        return $this->errorResponse("data not found", 404);
      }
      
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:20',
          'code' => 'required|string|max:20|unique:m_roles,code,' . $mRole->id . ',id',
          'level' => 'nullable|integer',
      ]);

      if ($validator->fails()) {
        return $this->validationErrorResponse(new ValidationException($validator));
      }

      $mRole->update([
          'name' => $request->name,
          'code' => $request->code,
          'level' => $request->level,
          'modified_by' => Auth::id() ?? 1, // Track modifier
          'modified_on' => now(),
      ]);

      return $this->successResponse($mRole);
  }

  // DELETE (Soft Delete) - DELETE /api/roles/{mRole}
  public function destroy(MRole $mRole){
    Log::info("destroy()");
      if ($mRole->IsDelete) {
        return $this->errorResponse("data not found", 404);
      }
      
      $mRole->update([
          'deleted_by' => Auth::id() ?? 1, // Track deleter
          'deleted_on' => now(),
          'is_delete' => true,
      ]);

      return $this->successResponse($mRole, 204);
  }
}
