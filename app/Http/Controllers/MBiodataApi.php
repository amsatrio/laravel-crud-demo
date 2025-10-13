<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\MBiodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MBiodataApi extends Controller
{
    use ApiResponseTrait;

    // READ (All) - GET /api/m-biodata
    public function index()
    {
        $mBiodatas = MBiodata::all();

        return $this->successResponse($mBiodatas);
    }

    // READ (One) - GET /api/m-biodata/{id}
    public function show(string $id)
    {
        $mBiodata = MBiodata::where("id", $id)->firstOrFail();

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

        $mBiodata = MBiodata::where("id", $id)->firstOrFail();


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
        $mBiodata = MBiodata::where("id", $id)->firstOrFail();

        $mBiodata->delete();

        return $this->successResponse(null, 'success', 200);
    }
}
