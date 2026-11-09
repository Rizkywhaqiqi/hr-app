<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    /**
     * Daftar lokasi.
     */
    public function index(): View
    {
        $items = Location::withCount('employees as employee_count')
            ->orderBy('name')
            ->get()
            ->map(fn (Location $l) => [
                'name' => $l->name,
                'employee_count' => $l->employee_count,
                'edit_url' => route('locations.edit', $l),
                'destroy_url' => route('locations.destroy', $l),
            ]);

        return view('master.index', [
            'title' => 'Data Lokasi',
            'itemLabel' => 'Lokasi',
            'items' => $items,
            'createUrl' => route('locations.create'),
            'listLabel' => 'lokasi',
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'exists' => false,
            'itemLabel' => 'Lokasi',
            'name' => '',
            'action' => route('locations.store'),
            'backUrl' => route('locations.index'),
        ]);
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        Location::create($request->validated());

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Location $location): View
    {
        return view('master.form', [
            'exists' => true,
            'itemLabel' => 'Lokasi',
            'name' => $location->name,
            'action' => route('locations.update', $location),
            'backUrl' => route('locations.index'),
        ]);
    }

    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($request->validated());

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $used = $location->employees()->count();

        if ($used > 0) {
            return back()->with('error', "Lokasi \"{$location->name}\" tidak bisa dihapus karena dipakai {$used} karyawan.");
        }

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}