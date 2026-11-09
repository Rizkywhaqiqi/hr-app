@extends('layouts.app')

@section('title', 'Laporan Karyawan')

@section('content')
    <div class="grid grid-2">
        <div class="card">
            <h2>Rekap Jumlah Karyawan per Brand</h2>
            <table>
                <thead>
                    <tr><th>Brand</th><th>Jumlah</th></tr>
                </thead>
                <tbody>
                    @forelse ($perBrand as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td><strong>{{ $row->total }}</strong></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="empty">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Ringkasan Status Karyawan</h2>
            @php
                $activeCount = $employees->where('is_active', true)->count();
                $inactiveCount = $employees->where('is_active', false)->count();
            @endphp
            <table>
                <tbody>
                    <tr>
                        <td><span class="badge active">Aktif</span></td>
                        <td><strong>{{ $activeCount }}</strong></td>
                    </tr>
                    <tr>
                        <td><span class="badge inactive">Nonaktif</span></td>
                        <td><strong>{{ $inactiveCount }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><strong>{{ $employees->count() }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="flex" style="justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <h2 class="mb-0">Daftar Karyawan ({{ $employees->count() }})</h2>
            <div class="flex" style="gap:8px;">
                <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-green">Ekspor CSV / Excel</a>
                <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-primary">Ekspor PDF</a>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.index') }}" class="mt-2">
            <div class="filter-bar">
                <div class="form-group">
                    <label>Brand</label>
                    <select name="brand_id">
                        <option value="">Semua brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ (string)($filters['brand_id'] ?? '') === (string)$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Divisi</label>
                    <select name="division_id">
                        <option value="">Semua divisi</option>
                        @foreach ($divisions as $div)
                            <option value="{{ $div->id }}" {{ (string)($filters['division_id'] ?? '') === (string)$div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="location_id">
                        <option value="">Semua lokasi</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->id }}" {{ (string)($filters['location_id'] ?? '') === (string)$loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua status</option>
                        <option value="1" {{ ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($filters['status'] ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>

        <div class="mt-2 table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Nama</th><th>Brand</th><th>Divisi</th>
                        <th>Lokasi</th><th>Bergabung</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $emp)
                        <tr>
                            <td><a href="{{ route('employees.show', $emp) }}"><strong>{{ $emp->employee_id }}</strong></a></td>
                            <td>{{ $emp->full_name }}</td>
                            <td>{{ $emp->brand?->name }}</td>
                            <td>{{ $emp->division?->name }}</td>
                            <td>{{ $emp->location?->name }}</td>
                            <td>{{ $emp->joined_date->format('d M Y') }}</td>
                            <td><span class="badge {{ $emp->is_active ? 'active' : 'inactive' }}">{{ $emp->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Tidak ada data untuk laporan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection