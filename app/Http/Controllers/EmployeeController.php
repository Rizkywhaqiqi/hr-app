<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Brand;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Daftar karyawan + pencarian/filter.
     */
    public function index(Request $request): View
    {
        $employees = Employee::query()
            ->with(['brand', 'division', 'location'])
            ->filter($request->only(['search', 'brand_id', 'division_id', 'location_id', 'status']))
            ->orderBy('employee_id')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'brands' => Brand::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'filters' => $request->only(['search', 'brand_id', 'division_id', 'location_id', 'status']),
        ]);
    }

    /**
     * Form tambah karyawan.
     */
    public function create(): View
    {
        return view('employees.form', $this->formData(new Employee()));
    }

    /**
     * Simpan karyawan baru.
     */
    public function store(EmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // Bila dibuat langsung sebagai nonaktif, catat waktunya.
        $data['deactivated_at'] = $data['is_active'] ? null : now();

        $employee = Employee::create($data);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Detail karyawan.
     */
    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Form ubah karyawan.
     */
    public function edit(Employee $employee): View
    {
        return view('employees.form', $this->formData($employee));
    }

    /**
     * Perbarui data karyawan.
     */
    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();

        // Catat waktu nonaktif saat status berubah menjadi nonaktif; kosongkan saat aktif kembali.
        if ($data['is_active']) {
            $data['deactivated_at'] = null;
        } elseif ($employee->deactivated_at === null) {
            $data['deactivated_at'] = now();
        }

        $employee->update($data);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Ubah status aktif/nonaktif.
     */
    public function toggleStatus(Employee $employee): RedirectResponse
    {
        $newActive = ! $employee->is_active;

        $employee->update([
            'is_active' => $newActive,
            'deactivated_at' => $newActive ? null : now(),
        ]);

        $status = $newActive ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status karyawan {$employee->full_name} berhasil {$status}.");
    }

    /**
     * Data bersama untuk form tambah/ubah.
     *
     * @return array<string, mixed>
     */
    private function formData(Employee $employee): array
    {
        return [
            'employee' => $employee,
            'brands' => Brand::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ];
    }
}