<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Systems\BiovetTechCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Throwable;

class CustomersController extends Controller
{
    public function index() {
        $customers = BiovetTechCustomer::all();

        return view('templates.admin.customers', compact('customers'));
    }

    public function store(Request $request)
{
    try {

        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:100',
            'address'      => 'nullable|string',
            'tin_number'   => 'nullable|string|max:50',
            'vat_number'   => 'nullable|string|max:50',
        ]);

        $exists = BiovetTechCustomer::where('full_name', $validated['full_name'])
                    ->where('phone', $validated['phone'])
                    ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Customer already exists.');
        }

        DB::beginTransaction();

        BiovetTechCustomer::create($validated);

        DB::commit();

        return redirect()->back()
            ->with('success', 'Customer registered successfully.');

    }
    catch (QueryException $e) {

        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Database error occurred. Please try again.');

    }
    catch (\Illuminate\Validation\ValidationException $e) {

        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();

    }
    catch (Throwable $e) {

        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Unexpected error occurred. Contact administrator.');
    }
}

    public function update(Request $request) {
        $request->validate([
            'auto_id'      => 'required|exists:biovet_tech_customers,auto_id',
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:100',
            'address'      => 'nullable|string',
            'tin_number'   => 'nullable|string|max:50',
            'vat_number'   => 'nullable|string|max:50',
        ]);

        $customer = BiovetTechCustomer::findOrFail($request->auto_id);
        $customer->update($request->all());

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Request $request) {
        $request->validate(['auto_id' => 'required|exists:biovet_tech_customers,auto_id']);
        $customer = BiovetTechCustomer::findOrFail($request->auto_id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }
}

