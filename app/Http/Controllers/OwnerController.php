<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Http\Requests\StoreOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $owners = Owner::withCount('pets')->latest()->paginate(10);
        return view('owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('owners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOwnerRequest $request): RedirectResponse
    {
        Owner::create($request->validated());

        return redirect()->route('owners.index')
            ->with('success', 'Dueño registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Owner $owner): View
    {
        $owner->load('pets');
        return view('owners.show', compact('owner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner): View
    {
        return view('owners.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOwnerRequest $request, Owner $owner): RedirectResponse
    {
        $owner->update($request->validated());

        return redirect()->route('owners.show', $owner)
            ->with('success', 'Datos del dueño actualizados exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Owner $owner): RedirectResponse
    {
        $owner->delete();

        return redirect()->route('owners.index')
            ->with('success', 'Dueño eliminado exitosamente.');
    }
}
