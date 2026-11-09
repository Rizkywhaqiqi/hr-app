<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PositionController extends Controller
{
    /**
     * Daftar jabatan.
     */
    public function index(): View
    {
        $items = Position::withCount('employees as employee_count')
            ->orderBy('name')
            ->get()
            ->map(fn (Position $p) => [
                'name' => $p->name,
                'employee_count' => $p->employee_count,
                'edit_url' => route('positions.edit', $p),
                'destroy_url' => route('positions.destroy', $p),
            ]);

        return view('master.index', [
            'title' => 'Data Jabatan',
            'itemLabel' => 'Jabatan',
            'items' => $items,
            'createUrl' => route('positions.create'),
            'listLabel' => 'jabatan',
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'exists' => false,
            'itemLabel' => 'Jabatan',
            'name' => '',
            'action' => route('positions.store'),
            'backUrl' => route('positions.index'),
        ]);
    }

    public function store(PositionRequest $request): RedirectResponse
    {
        Position::create($request->validated());

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Position $position): View
    {
        return view('master.form', [
            'exists' => true,
            'itemLabel' => 'Jabatan',
            'name' => $position->name,
            'action' => route('positions.update', $position),
            'backUrl' => route('positions.index'),
        ]);
    }

    public function update(PositionRequest $request, Position $position): RedirectResponse
    {
        $position->update($request->validated());

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        $used = $position->employees()->count();

        if ($used > 0) {
            return back()->with('error', "Jabatan \"{$position->name}\" tidak bisa dihapus karena dipakai {$used} karyawan.");
        }

        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}