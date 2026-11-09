@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
    <div class="card">
        <div class="flex" style="justify-content:space-between;align-items:center;">
            <h2 class="mb-0">Detail Karyawan</h2>
            <span class="badge {{ $employee->is_active ? 'active' : 'inactive' }}">{{ $employee->statusLabel() }}</span>
        </div>

        <dl class="kv mt-2">
            <dt>ID Karyawan</dt><dd>{{ $employee->employee_id }}</dd>
            <dt>Nama Lengkap</dt><dd>{{ $employee->full_name }}</dd>
            <dt>Brand</dt><dd>{{ $employee->brand?->name }}</dd>
            <dt>Jabatan</dt><dd>{{ $employee->position?->name }}</dd>
            <dt>Divisi</dt><dd>{{ $employee->division?->name }}</dd>
            <dt>Lokasi Kerja</dt><dd>{{ $employee->location?->name }}</dd>
            <dt>Tanggal Bergabung</dt><dd>{{ $employee->joined_date->format('d M Y') }}</dd>
            <dt>Status</dt><dd>{{ $employee->statusLabel() }}</dd>
            @if ($employee->deactivated_at)
                <dt>Dinonaktifkan Pada</dt><dd>{{ $employee->deactivated_at->format('d M Y H:i') }}</dd>
            @endif
            <dt>Perbarui Terakhir</dt><dd>{{ $employee->updated_at->format('d M Y H:i') }}</dd>
        </dl>

        <div class="flex mt-2" style="margin-top:22px;">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Ubah Data</a>
            <form method="POST" action="{{ route('employees.status', $employee) }}" onsubmit="return confirm('Yakin mengubah status {{ $employee->full_name }}?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $employee->is_active ? 'btn-red' : 'btn-green' }}">
                    {{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <a href="{{ route('employees.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>
@endsection