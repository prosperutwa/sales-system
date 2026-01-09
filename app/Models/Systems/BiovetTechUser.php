<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiovetTechUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biovet_tech_users';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phonenumber',
        'status',
        'role',
    ];
}
