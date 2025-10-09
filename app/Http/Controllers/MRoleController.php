<?php

namespace App\Http\Controllers;

use App\Models\MRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MRoleController extends Controller
{
  // READ (All) - GET /api/roles
  public function index(){
      Log::info("index()");
      $roles = MRole::where('is_delete', false)->get(); // Filter active roles
      return response()->json($roles, 200);
  }

  // READ (One) - GET /api/roles/{mRole}
  public function show(MRole $mRole){
    Log::info("show()");
      if ($mRole->IsDelete) {
          return response()->json(['message' => 'Role not found.'], 404);
      }
      return response()->json($mRole, 200);
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
          return response()->json($validator->errors(), 422);
      }

      $role = MRole::create([
          'name' => $request->name,
          'code' => $request->code,
          'level' => $request->level,
          'created_by' => Auth::id() ?? 1, // Assuming user ID 1 for unauthenticated
          'created_on' => now(),
      ]);

      return response()->json($role, 201);
  }

  // UPDATE - PUT/PATCH /api/roles/{mRole}
  public function update(Request $request, MRole $mRole){
    Log::info("update()");
      if ($mRole->IsDelete) {
          return response()->json(['message' => 'Role not found.'], 404);
      }
      
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:20',
          'code' => 'required|string|max:20|unique:m_roles,code,' . $mRole->id . ',id',
          'level' => 'nullable|integer',
      ]);

      if ($validator->fails()) {
          return response()->json($validator->errors(), 422);
      }

      $mRole->update([
          'name' => $request->name,
          'code' => $request->code,
          'level' => $request->level,
          'modified_by' => Auth::id() ?? 1, // Track modifier
          'modified_on' => now(),
      ]);

      return response()->json($mRole, 200);
  }

  // DELETE (Soft Delete) - DELETE /api/roles/{mRole}
  public function destroy(MRole $mRole){
    Log::info("destroy()");
      if ($mRole->IsDelete) {
          return response()->json(['message' => 'Role not found.'], 404);
      }
      
      $mRole->update([
          'deleted_by' => Auth::id() ?? 1, // Track deleter
          'deleted_on' => now(),
          'is_delete' => true,
      ]);

      return response()->json(['message' => 'Role deleted successfully'], 204);
  }
}
