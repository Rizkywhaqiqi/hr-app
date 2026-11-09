<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Daftar brand.
     */
    public function index(): View
    {
        $items = Brand::withCount('employees as employee_count')
            ->orderBy('name')
            ->get()
            ->map(fn (Brand $b) => [
                'name' => $b->name,
                'employee_count' => $b->employee_count,
                'edit_url' => route('brands.edit', $b),
                'destroy_url' => route('brands.destroy', $b),
            ]);

        return view('master.index', [
            'title' => 'Data Brand',
            'itemLabel' => 'Brand',
            'items' => $items,
            'createUrl' => route('brands.create'),
            'listLabel' => 'brand',
        ]);
    }

    public function create(): View
    {
        return view('master.form', [
            'exists' => false,
            'itemLabel' => 'Brand',
            'name' => '',
            'action' => route('brands.store'),
            'backUrl' => route('brands.index'),
        ]);
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        Brand::create($request->validated());

        return redirect()->route('brands.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function edit(Brand $brand): View
    {
        return view('master.form', [
            'exists' => true,
            'itemLabel' => 'Brand',
            'name' => $brand->name,
            'action' => route('brands.update', $brand),
            'backUrl' => route('brands.index'),
        ]);
    }

    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        $brand->update($request->validated());

        return redirect()->route('brands.index')->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $used = $brand->employees()->count();

        if ($used > 0) {
            return back()->with('error', "Brand \"{$brand->name}\" tidak bisa dihapus karena dipakai {$used} karyawan.");
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand berhasil dihapus.');
    }
}