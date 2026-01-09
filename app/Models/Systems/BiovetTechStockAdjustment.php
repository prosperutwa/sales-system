<?php

namespace App\Models\Systems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiovetTechStockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'biovet_tech_stock_adjustments';
    protected $primaryKey = 'auto_id';

    protected $fillable = [
        'product_id',
        'quantity',
    ];

    /**
     * Relationship
     * Stock Adjustment -> Product
     */
    public function product()
    {
        return $this->belongsTo(
            BiovetTechProduct::class,
            'product_id',
            'auto_id'
        );
    }
}
