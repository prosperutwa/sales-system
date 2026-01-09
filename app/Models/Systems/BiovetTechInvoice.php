<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiovetTechInvoice extends Model
{
    use HasFactory;

    protected $table = 'biovet_tech_invoices';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'customer_id',
        'user_id',
        'invoice_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'invoice_date'    => 'date',
        'subtotal'        => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount'    => 'decimal:2',
    ];

    /**
     * ======================
     * Relations
     * ======================
     */

    // Invoice -> Customer
    public function customer()
    {
        return $this->belongsTo(BiovetTechCustomer::class, 'customer_id', 'auto_id');
    }

    // Invoice -> User (Seller/Admin)
    public function user()
    {
        return $this->belongsTo(BiovetTechUser::class, 'user_id', 'auto_id');
    }

    // Invoice -> Items
    public function items()
    {
        return $this->hasMany(BiovetTechInvoiceItem::class, 'invoice_id', 'auto_id');
    }

    // Invoice -> Payments
    public function payments()
    {
        return $this->hasMany(BiovetTechPayment::class, 'invoice_id', 'auto_id');
    }
}
