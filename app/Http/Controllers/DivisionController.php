<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisionRequest;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DivisionController extends Controller
{
    /**
     * Daftar divisi.
     */
    public function index(): View
    {
        $items = Division::withCount('employees as employee_count')
            ->orderBy('name')
            ->get()
            ->map(fn (Division $d) => [
                'name' => $d->name,
                'employee_count' => $d->employee_count,
                'edit_url' => route('divisions.edit', $d),
                'destroy_url' => route('divisions.destroy', $d),
            ]);

        return view('master.index', [
            'title' => 'Data Divisi',
            'itemLabel' => 'Divisi',
            'items' => $items,
            'createUrl' => route('divisions.create'),
            'listLabel' => 'divisi',
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'exists' => false,
            'itemLabel' => 'Divisi',
            'name' => '',
            'action' => route('divisions.store'),
            'backUrl' => route('divisions.index'),
        ]);
    }

    public function store(DivisionRequest $request): RedirectResponse
    {
        Division::create($request->validated());

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division): View
    {
        return view('master.form', [
            'exists' => true,
            'itemLabel' => 'Divisi',
            'name' => $division->name,
            'action' => route('divisions.update', $division),
            'backUrl' => route('divisions.index'),
        ]);
    }

    public function update(DivisionRequest $request, Division $division): RedirectResponse
    {
        $division->update($request->validated());

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        $used = $division->employees()->count();

        if ($used > 0) {
            return back()->with('error', "Divisi \"{$division->name}\" tidak bisa dihapus karena dipakai {$used} karyawan.");
        }

        $division->delete();

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}