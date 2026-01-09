<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Systems\BiovetTechProduct;
use App\Models\Systems\BiovetTechStockAdjustment;

class ProductsController extends Controller
{
    public function index(){

        $products = BiovetTechProduct::all();
        return view('templates.admin.products', compact('products'));

    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'name'            => 'required|string|max:255',
                'description'     => 'nullable|string',
                'buying_price'    => 'required|numeric|min:0',
                'selling_price'   => ['required', 'numeric', 'gt:buying_price'],
                'stock_quantinty' => 'required|integer|gt:0', 
            ],
            [
                'selling_price.gt' => 'Selling price must be greater than buying price.',
                'stock_quantinty.gt' => 'Stock quantity must be greater than 0.',
            ]
        );

        $existing = BiovetTechProduct::where('name', $request->name)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Product already exists.');
        }

        DB::transaction(function () use ($request) {
            BiovetTechProduct::create([
                'name'             => $request->name,
                'description'      => $request->description,
                'buying_price'     => $request->buying_price,
                'selling_price'    => $request->selling_price,
                'stock_quantinty'  => $request->stock_quantinty,
                'remain_quantity'  => $request->stock_quantinty,
            ]);
        });

        return redirect()->back()->with('success', 'Product registered successfully.');
    }

    public function update(Request $request)
    {
        $request->validate(
            [
                'auto_id'         => 'required|exists:biovet_tech_products,auto_id',
                'name'            => 'required|string|max:255',
                'description'     => 'nullable|string',
                'buying_price'    => 'required|numeric|min:0',
                'selling_price'   => ['required', 'numeric', 'gt:buying_price'],
                'stock_quantinty' => 'required|integer|gt:0',
            ],
            [
                'selling_price.gt' => 'Selling price must be greater than buying price.',
                'stock_quantinty.gt' => 'Stock quantity must be greater than 0.',
            ]
        );

        $exists = BiovetTechProduct::where('name', $request->name)
        ->where('auto_id', '!=', $request->auto_id)
        ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Product name already exists.');
        }

        DB::transaction(function () use ($request) {

            $product = BiovetTechProduct::findOrFail($request->auto_id);

            if ($request->stock_quantinty > $product->stock_quantinty) {
                $difference = $request->stock_quantinty - $product->stock_quantinty;
                $product->remain_quantity += $difference;
            }

            $product->update([
                'name'            => $request->name,
                'description'     => $request->description,
                'buying_price'    => $request->buying_price,
                'selling_price'   => $request->selling_price,
                'stock_quantinty' => $request->stock_quantinty,
            ]);
        });

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'auto_id' => 'required|exists:biovet_tech_products,auto_id',
        ]);

        $product = BiovetTechProduct::findOrFail($request->auto_id);

        if ($product->remain_quantity > 0) {
            return redirect()->back()->with(
                'error',
                'You cannot delete a product with remaining stock.'
            );
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function addQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:biovet_tech_products,auto_id',
            'quantity'   => 'required|integer|gt:0',
        ]);

        DB::transaction(function () use ($request) {

            $product = BiovetTechProduct::lockForUpdate()
            ->where('auto_id', $request->product_id)
            ->firstOrFail();

            BiovetTechStockAdjustment::create([
                'product_id' => $product->auto_id,
                'quantity'   => $request->quantity,
            ]);

            $product->update([
                'remain_quantity' => $product->remain_quantity + $request->quantity,
            ]);
        });

        return redirect()->back()->with('success', 'Stock quantity added successfully.');
    }


}
