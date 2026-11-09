@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="flex" style="justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h2 class="mb-0" style="font-size:16px;color:var(--navy);">Daftar {{ $itemLabel }}</h2>
            <a href="{{ $createUrl }}" class="btn btn-primary">+ Tambah {{ $itemLabel }}</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ $itemLabel }}</th>
                    <th>Jumlah Karyawan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ e($item['name']) }}</td>
                        <td>{{ $item['employee_count'] }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ $item['edit_url'] }}" class="btn btn-outline btn-sm">Ubah</a>
                                <form method="POST" action="{{ $item['destroy_url'] }}" onsubmit="return confirm('Yakin hapus {{ e($item['name']) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-red">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty">Belum ada {{ $listLabel }}. Klik "+ Tambah" untuk menambah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection