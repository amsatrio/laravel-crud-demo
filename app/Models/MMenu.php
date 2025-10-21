<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MMenu extends Model
{
    protected $table = 'm_menu';

     protected $fillable = [
        'name',
        'actions',
        'code',
        
        'created_by',
        'created_on',
        'modified_by',
        'modified_on',
        'deleted_by',
        'deleted_on',
        'is_delete',
    ];

    public $timestamps = false; 
}
