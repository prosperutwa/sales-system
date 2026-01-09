<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Systems\BiovetTechUser;

class BiovetTechAuth extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biovet_tech_auth';
    protected $primaryKey = 'auto_id';

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'status',
    ];

    /**
     * Relation: Auth -> User
     */
    public function user()
    {
        return $this->belongsTo(BiovetTechUser::class, 'user_id', 'auto_id');
    }
}
