<?php

namespace App\Models\Systems;

use App\Models\Auth\BiovetTechAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    public function auth()
    {
        return $this->hasOne(
            BiovetTechAuth::class,
            'user_id',
            'auto_id'   
        );
    }

    /**
     * Accessor: Full Name
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
