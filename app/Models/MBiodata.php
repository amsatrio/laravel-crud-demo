<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBiodata extends Model
{
    use HasFactory;

    // Assuming the table name is 'm_biodatas' based on the model name
    protected $table = 'm_biodata';

    // Disable Laravel's default timestamps and soft delete columns to manage them manually
    // However, for typical Laravel projects, let the framework handle created_at, updated_at
    // For simplicity, we'll assume Laravel's default 'id', 'created_at', 'updated_at' are handled
    // and manually track 'CreatedBy', 'ModifiedBy', etc. if needed, or rely on Eloquent events.
    
    // For a typical CRUD, we'll focus on the fields to be set by the user/system:
    protected $fillable = [
        'fullname',
        'mobile_phone',
        'image',
        'image_path',
        
        'created_by',
        'created_on',
        'modified_by',
        'modified_on',
        'deleted_by',
        'deleted_on',
        'is_delete',
    ];

    // Disable default timestamps if you use your custom column names (CreatedOn, ModifiedOn)
    public $timestamps = false; 

    // Define custom column names for created_at, updated_at if you want to use the Go names,
    // but keep in mind that the database migration must match. 
    // If you stick to Eloquent's convention, it's easier.
    // Let's assume you'll use Laravel's defaults for created/updated/deleted tracking.
}