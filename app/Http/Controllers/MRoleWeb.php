<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class MRoleWeb extends Controller {
        // Show the list view (Index)
        public function index(){
            return view('m-role.index');
        }
        public function detail($id){
            return view('m-role.detail', ['roleId' => $id]);
        }
    
        // Show the create form view
        public function create(){
            return view('m-role.create');
        }
    
        // Show the edit form view
        public function edit($id){
            return view('m-role.edit', ['roleId' => $id]);
        }
}