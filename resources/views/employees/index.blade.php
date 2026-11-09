@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('employees.index') }}">
            <div class="filter-bar">
                <div class="form-group">
                    <label>Kata kunci</label>
                    <input type="text" name="search" value="{{ old('search', $filters['search'] ?? '') }}" placeholder="Nama atau ID karyawan">
                </div>
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
                    <a href="{{ route('employees.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="flex" style="justify-content:space-between;margin-bottom:14px;">
            <h2 class="mb-0" style="font-size:16px;color:var(--navy);">Daftar Karyawan</h2>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">+ Tambah Karyawan</a>
        </div>

        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID Karyawan</th>
                    <th>Nama Lengkap</th>
                    <th>Brand</th>
                    <th>Jabatan</th>
                    <th>Divisi</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $emp)
                    <tr>
                        <td><a href="{{ route('employees.show', $emp) }}"><strong>{{ $emp->employee_id }}</strong></a></td>
                        <td>{{ $emp->full_name }}</td>
                        <td>{{ $emp->brand?->name }}</td>
                        <td>{{ $emp->position?->name }}</td>
                        <td>{{ $emp->division?->name }}</td>
                        <td>{{ $emp->location?->name }}</td>
                        <td>
                            <span class="badge {{ $emp->is_active ? 'active' : 'inactive' }}">{{ $emp->statusLabel() }}</span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('employees.show', $emp) }}" class="btn btn-outline btn-sm">Lihat</a>
                                <a href="{{ route('employees.edit', $emp) }}" class="btn btn-outline btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('employees.status', $emp) }}" onsubmit="return confirm('Yakin mengubah status {{ $emp->full_name }}?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $emp->is_active ? 'btn-red' : 'btn-green' }}">
                                        {{ $emp->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">Tidak ada karyawan yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="mt-2">
            {{ $employees->links() }}
        </div>
    </div>
@endsection