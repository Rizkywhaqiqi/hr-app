@extends('layouts.app')

@section('title', 'Dashboard HR')

@section('content')
    @php
        $totalSafe = max($total, 1);
        $activePct = (int) round($active / $totalSafe * 100);
        $inactivePct = 100 - $activePct;
        $maxBrand = max((int) ($perBrand->max('total') ?? 0), 1);
        $maxDivision = max((int) ($perDivision->max('total') ?? 0), 1);
        $maxLocation = max((int) ($perLocation->max('total') ?? 0), 1);
    @endphp

    <div class="grid grid-4">
        <a class="stat blue" href="{{ route('employees.index') }}">
            <div class="num">{{ $total }}</div>
            <div class="lbl">Total Karyawan</div>
            <div class="go">Lihat semua &rarr;</div>
        </a>
        <a class="stat green" href="{{ route('employees.index', ['status' => '1']) }}">
            <div class="num">{{ $active }}</div>
            <div class="lbl">Karyawan Aktif</div>
            <div class="go">Lihat aktif &rarr;</div>
        </a>
        <a class="stat red" href="{{ route('employees.index', ['status' => '0']) }}">
            <div class="num">{{ $inactive }}</div>
            <div class="lbl">Karyawan Nonaktif</div>
            <div class="go">Lihat nonaktif &rarr;</div>
        </a>
        <a class="stat" href="{{ route('brands.index') }}">
            <div class="num">{{ count($perBrand) }}</div>
            <div class="lbl">Brand</div>
            <div class="go">Kelola brand &rarr;</div>
        </a>
    </div>

    <div class="chart-grid mt-2">
        <div class="card">
            <h2>Status Karyawan</h2>
            <div class="donut-wrap">
                <div class="donut" style="background:conic-gradient(var(--green) 0 {{ $activePct }}%, #cfd8e3 {{ $activePct }}% 100%);">
                    <div class="donut-center"><span class="n">{{ $total }}</span><span class="t">karyawan</span></div>
                </div>
                <div class="legend">
                    <span><span class="dot" style="background:var(--green)"></span>Aktif &mdash; <strong>{{ $active }}</strong> ({{ $activePct }}%)</span>
                    <span><span class="dot" style="background:#cfd8e3"></span>Nonaktif &mdash; <strong>{{ $inactive }}</strong> ({{ $inactivePct }}%)</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Karyawan per Brand</h2>
            @forelse ($perBrand as $row)
                <a class="bar-row" href="{{ route('employees.index', ['brand_id' => $row->id]) }}" title="Lihat karyawan {{ $row->name }}">
                    <span class="bar-label">{{ $row->name }}</span>
                    <span class="bar-track"><span class="bar-fill" style="width:{{ (int) round($row->total / $maxBrand * 100) }}%"></span></span>
                    <span class="bar-value">{{ $row->total }}</span>
                </a>
            @empty
                <p class="empty">Belum ada data karyawan.</p>
            @endforelse
        </div>
    </div>

    <div class="chart-grid mt-2">
        <div class="card">
            <h2>Karyawan per Divisi</h2>
            @forelse ($perDivision as $row)
                <a class="bar-row" href="{{ route('employees.index', ['division_id' => $row->id]) }}" title="Lihat karyawan divisi {{ $row->name }}">
                    <span class="bar-label">{{ $row->name }}</span>
                    <span class="bar-track"><span class="bar-fill" style="width:{{ (int) round($row->total / $maxDivision * 100) }}%"></span></span>
                    <span class="bar-value">{{ $row->total }}</span>
                </a>
            @empty
                <p class="empty">Belum ada data karyawan.</p>
            @endforelse
        </div>

        <div class="card">
            <h2>Karyawan per Lokasi</h2>
            @forelse ($perLocation as $row)
                <a class="bar-row" href="{{ route('employees.index', ['location_id' => $row->id]) }}" title="Lihat karyawan di {{ $row->name }}">
                    <span class="bar-label">{{ $row->name }}</span>
                    <span class="bar-track"><span class="bar-fill" style="width:{{ (int) round($row->total / $maxLocation * 100) }}%"></span></span>
                    <span class="bar-value">{{ $row->total }}</span>
                </a>
            @empty
                <p class="empty">Belum ada data karyawan.</p>
            @endforelse
        </div>
    </div>

    <p class="muted mt-2">Klik kartu atau batang grafik untuk membuka daftar karyawan yang sudah terfilter.</p>
@endsection