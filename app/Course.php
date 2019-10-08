<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	protected $table='courses';
    protected $fillable = [
        'name', 'address', 'city', 'short_name', 'country', 'created_at', 'updated_at', 'user_course_id'
    ];
}
