<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class MRoleWeb extends Controller {
        // Show the list view (Index)
        public function index(){
            Log::info("index()");
            return view('m-role.index');
        }
        public function detail($id){
            Log::info("detail()");
            return view('m-role.detail', ['roleId' => $id]);
        }
    
        // Show the create form view
        public function create(){
            Log::info("create()");
            return view('m-role.create');
        }
    
        // Show the edit form view
        public function edit($id){
            Log::info("edit()");
            return view('m-role.edit', ['roleId' => $id]);
        }
}