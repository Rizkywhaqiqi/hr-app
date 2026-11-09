<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan angka HR.
     */
    public function __invoke(): View
    {
        $total = Employee::count();
        $active = Employee::where('is_active', true)->count();
        $inactive = Employee::where('is_active', false)->count();

        // Rekap per brand / divisi / lokasi, dihitung ulang tiap halaman dibuka.
        $perBrand = Brand::withCount('employees as total')->orderByDesc('total')->get();
        $perDivision = Division::withCount('employees as total')->orderByDesc('total')->get();
        $perLocation = Location::withCount('employees as total')->orderByDesc('total')->get();

        return view('dashboard', compact(
            'total',
            'active',
            'inactive',
            'perBrand',
            'perDivision',
            'perLocation',
        ));
    }
}