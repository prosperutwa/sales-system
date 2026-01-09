<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiovetTechInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'biovet_tech_invoice_items';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'total_price'=> 'decimal:2',
    ];

    /**
     * Relations
     */

    // Invoice Item -> Invoice
    public function invoice()
    {
        return $this->belongsTo(BiovetTechInvoice::class, 'invoice_id', 'auto_id');
    }

    // Invoice Item -> Product
    public function product()
    {
        return $this->belongsTo(BiovetTechProduct::class, 'product_id', 'auto_id');
    }
}
