<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Tampilkan laporan rekap + daftar karyawan (bisa difilter).
     */
    public function index(Request $request): View
    {
        $perBrand = Brand::withCount('employees as total')->orderByDesc('total')->get();

        $employees = Employee::query()
            ->with(['brand', 'division', 'location'])
            ->filter($request->only(['search', 'brand_id', 'division_id', 'location_id', 'status']))
            ->orderBy('employee_id')
            ->get();

        return view('reports.index', [
            'perBrand' => $perBrand,
            'employees' => $employees,
            'brands' => Brand::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'filters' => $request->only(['search', 'brand_id', 'division_id', 'location_id', 'status']),
        ]);
    }

    /**
     * Ekspor data ke CSV (mengikuti filter yang sedang aktif).
     */
    public function export(Request $request): StreamedResponse
    {
        $employees = Employee::query()
            ->with(['brand', 'division', 'location'])
            ->filter($request->only(['search', 'brand_id', 'division_id', 'location_id', 'status']))
            ->orderBy('employee_id')
            ->get();

        $filename = 'laporan-karyawan-'.now()->format('Ymd-His').'.csv';

        return Response::stream(function () use ($employees) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 supaya tanda aksen terbaca benar di Excel.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID Karyawan',
                'Nama Lengkap',
                'Brand',
                'Jabatan',
                'Divisi',
                'Lokasi Kerja',
                'Tanggal Bergabung',
                'Status',
            ]);

            foreach ($employees as $employee) {
                fputcsv($out, [
                    $employee->employee_id,
                    $employee->full_name,
                    $employee->brand?->name,
                    $employee->position?->name,
                    $employee->division?->name,
                    $employee->location?->name,
                    $employee->joined_date->format('Y-m-d'),
                    $employee->statusLabel(),
                ]);
            }

            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Ekspor data ke PDF (mengikuti filter yang sedang aktif).
     */
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'brand_id', 'division_id', 'location_id', 'status']);

        $employees = Employee::query()
            ->with(['brand', 'division', 'location'])
            ->filter($filters)
            ->orderBy('employee_id')
            ->get();

        $perBrand = Brand::withCount('employees as total')->orderByDesc('total')->get();

        $pdf = Pdf::loadView('reports.pdf', [
            'employees' => $employees,
            'perBrand' => $perBrand,
            'activeCount' => $employees->where('is_active', true)->count(),
            'inactiveCount' => $employees->where('is_active', false)->count(),
            'generatedAt' => now(),
            'filters' => $filters,
        ])->setPaper('a4', 'portrait');

        $filename = 'JOJO-GROUP-MANAGEMENT-laporan-karyawan-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($filename);
    }
}