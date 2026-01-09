<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiovetTechCompanySetting extends Model
{
    use HasFactory;

    protected $table = 'biovet_tech_company_settings';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_logo',
        'company_tin',
        'default_currency',
        'tax_percentage',
        'invoice_footer_note',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
    ];
}
