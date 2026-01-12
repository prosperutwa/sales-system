<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiovetTechCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biovet_tech_customers';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'full_name',
        'phone',
        'company_name',
        'email',
        'address',
        'tin_number',
        'vat_number',
    ];

    /**
     * Relations
     */

    // Customer -> Invoices
    public function invoices()
    {
        return $this->hasMany(BiovetTechInvoice::class, 'customer_id', 'auto_id');
    }
}
