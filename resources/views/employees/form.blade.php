@extends('layouts.app')

@section('title', $employee->exists ? 'Ubah Karyawan' : 'Tambah Karyawan')

@section('content')
    <div class="card">
        <h2>{{ $employee->exists ? 'Ubah Data Karyawan' : 'Tambah Karyawan Baru' }}</h2>

        <form method="POST"
              action="{{ $employee->exists ? route('employees.update', $employee) : route('employees.store') }}">
            @csrf
            @if ($employee->exists)
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="alert" style="background:#fdeaea;color:#a11d1d;border-color:#f3c1c1;">
                    <strong>Perbaiki hal berikut:</strong>
                    <ul style="margin:6px 0 0;padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="employee_id">ID Karyawan <span style="color:var(--red)">*</span></label>
                    <input type="text" id="employee_id" name="employee_id"
                           value="{{ old('employee_id', $employee->employee_id) }}"
                           placeholder="contoh: KS-001"
                           {{ $employee->exists ? 'readonly style=background:#f0f3f7' : '' }}>
                    <div class="help">Format: 2-5 huruf besar + tanda hubung + 3-6 angka (misal KS-001).</div>
                    @error('employee_id')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="full_name">Nama Lengkap <span style="color:var(--red)">*</span></label>
                    <input type="text" id="full_name" name="full_name"
                           value="{{ old('full_name', $employee->full_name) }}">
                    @error('full_name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="brand">Brand <span style="color:var(--red)">*</span></label>
                    <select id="brand" name="brand_id">
                        <option value="">— Pilih brand —</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ (string)old('brand_id', $employee->brand_id) === (string)$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="position_id">Jabatan <span style="color:var(--red)">*</span></label>
                    <select id="position_id" name="position_id">
                        <option value="">— Pilih jabatan —</option>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos->id }}" {{ (string)old('position_id', $employee->position_id) === (string)$pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                        @endforeach
                    </select>
                    <div class="help">Kelola daftar jabatan di menu <a href="{{ route('positions.index') }}">Jabatan</a>.</div>
                    @error('position_id')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="division">Divisi <span style="color:var(--red)">*</span></label>
                    <select id="division" name="division_id">
                        <option value="">— Pilih divisi —</option>
                        @foreach ($divisions as $div)
                            <option value="{{ $div->id }}" {{ (string)old('division_id', $employee->division_id) === (string)$div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                        @endforeach
                    </select>
                    @error('division_id')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="work_location">Lokasi Kerja <span style="color:var(--red)">*</span></label>
                    <select id="location_id" name="location_id">
                        <option value="">— Pilih lokasi —</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->id }}" {{ (string)old('location_id', $employee->location_id) === (string)$loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="joined_date">Tanggal Bergabung <span style="color:var(--red)">*</span></label>
                    <input type="date" id="joined_date" name="joined_date"
                           value="{{ old('joined_date', $employee->joined_date?->format('Y-m-d')) }}">
                    @error('joined_date')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Status <span style="color:var(--red)">*</span></label>
                    <div class="checkbox-line">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $employee->exists ? $employee->is_active : true) ? 'checked' : '' }}>
                        <label for="is_active" style="margin:0;font-weight:400;">Karyawan aktif</label>
                    </div>
                    @error('is_active')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="flex mt-2" style="margin-top:22px;">
                <button type="submit" class="btn btn-primary">{{ $employee->exists ? 'Simpan Perubahan' : 'Simpan Karyawan' }}</button>
                <a href="{{ $employee->exists ? route('employees.show', $employee) : route('employees.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection