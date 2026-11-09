@extends('layouts.app')

@section('title', ($exists ? 'Ubah ' : 'Tambah ') . $itemLabel)

@section('content')
    <div class="card">
        <h2>{{ $exists ? 'Ubah ' : 'Tambah ' }}{{ $itemLabel }}</h2>

        <form method="POST" action="{{ $action }}">
            @csrf
            @if ($exists)
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

            <div class="form-group">
                <label for="name">Nama {{ $itemLabel }} <span style="color:var(--red)">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $name) }}" maxlength="100">
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="flex" style="margin-top:22px;">
                <button type="submit" class="btn btn-primary">{{ $exists ? 'Simpan Perubahan' : 'Simpan' }}</button>
                <a href="{{ $backUrl }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection