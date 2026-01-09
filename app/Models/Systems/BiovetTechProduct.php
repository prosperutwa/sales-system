<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiovetTechProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biovet_tech_products';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'name',
        'description',
        'buying_price',
        'selling_price',
        'stock_quantinty',
        'remain_quantity',
    ];

    protected $casts = [
        'buying_price'   => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'stock_quantinty'=> 'integer',
        'remain_quantity'=> 'integer',
    ];

    /**
     * Relations
     */

    // Product -> Invoice Items
    public function invoiceItems()
    {
        return $this->hasMany(BiovetTechInvoiceItem::class, 'product_id', 'auto_id');
    }
}
