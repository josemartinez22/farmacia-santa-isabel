<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Suppliers/Index', [
            'filters' => Request::all(['search']),
            'suppliers' => Supplier::orderBy('name')
                ->filter(Request::only(['search']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($supplier) => [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'phone' => $supplier->phone
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Suppliers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $request->validated();

        Supplier::create([
            'name' => Request::get('name'),
            'phone' => Request::get('phone'),
        ]);

        return Redirect::route('suppliers')->with('success', 'Proveedor creado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return Inertia::render('Suppliers/Edit', [
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'phone' => $supplier->phone
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $request->validated();

        $supplier->update(Request::only('name', 'phone'));

        return Redirect::back()->with('success', 'Proveedor actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
