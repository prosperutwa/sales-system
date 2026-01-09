<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiovetTechPayment extends Model
{
    use HasFactory;

    protected $table = 'biovet_tech_payments';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'invoice_id',
        'amount_paid',
        'payment_method',
        'payment_date',
        'reference_number',
    ];

    protected $casts = [
        'amount_paid'  => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Relations
     */

    // Payment -> Invoice
    public function invoice()
    {
        return $this->belongsTo(BiovetTechInvoice::class, 'invoice_id', 'auto_id');
    }
}
