<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>JOJO GROUP MANAGEMENT - Laporan Karyawan</title>
    <style>
        @page { margin: 24px 28px; }
        * { font-family: 'DejaVu Sans', Arial, sans-serif; }
        body { color: #22303f; font-size: 10px; }
        .header { border-bottom: 3px solid #0f2b46; padding-bottom: 10px; margin-bottom: 12px; }
        .brand { font-size: 22px; font-weight: bold; color: #0f2b46; letter-spacing: 1px; }
        .brand .accent { color: #f2711c; }
        .subtitle { font-size: 12px; color: #6c7a8a; margin-top: 2px; }
        .meta { font-size: 9.5px; color: #6c7a8a; margin-top: 6px; }
        h2 { font-size: 12.5px; color: #0f2b46; margin: 14px 0 6px; }
        table { width: 100%; border-collapse: collapse; }
        .summary th, .summary td { border: 1px solid #cfd8e3; padding: 5px 8px; font-size: 10px; text-align: left; }
        .summary th { background: #0f2b46; color: #fff; }
        .summary td.r { text-align: right; }
        .emp { border: 1px solid #cfd8e3; border-radius: 6px; margin-bottom: 9px; page-break-inside: avoid; }
        .emp-head { background: #0f2b46; color: #fff; padding: 6px 10px; }
        .emp-head .id { font-weight: bold; }
        .emp-head .nm { margin-left: 8px; }
        .pill { float: right; font-size: 8.5px; padding: 1px 8px; border-radius: 8px; }
        .pill.on { background: #21ba45; color: #fff; }
        .pill.off { background: #9aa5b1; color: #fff; }
        .kv { width: 100%; border-collapse: collapse; }
        .kv td { padding: 4px 10px; font-size: 10px; vertical-align: top; border-bottom: 1px solid #eef2f7; }
        .kv td.k { color: #6c7a8a; width: 17%; }
        .kv td.v { width: 33%; }
        .empty { text-align: center; color: #6c7a8a; padding: 20px; }
        .footer { position: fixed; bottom: -6px; left: 0; right: 0; font-size: 8px; color: #9aa5b1; text-align: center; border-top: 1px solid #e3e8ef; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">JOJO <span class="accent">GROUP MANAGEMENT</span></div>
        <div class="subtitle">Laporan Data Karyawan (Detail)</div>
        <div class="meta">
            Dibuat: {{ $generatedAt->format('d M Y H:i') }} &nbsp;|&nbsp;
            Total: {{ $employees->count() }} karyawan &nbsp;|&nbsp;
            Aktif: {{ $activeCount }} &nbsp;|&nbsp; Nonaktif: {{ $inactiveCount }}
        </div>
    </div>

    <h2>Rekap Jumlah Karyawan per Brand</h2>
    <table class="summary">
        <thead>
            <tr><th>Brand</th><th style="text-align:right;">Jumlah</th></tr>
        </thead>
        <tbody>
            @foreach ($perBrand as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td class="r">{{ $row->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Detail Karyawan</h2>
    @forelse ($employees as $emp)
        <div class="emp">
            <div class="emp-head">
                <span class="pill {{ $emp->is_active ? 'on' : 'off' }}">{{ $emp->statusLabel() }}</span>
                <span class="id">{{ $emp->employee_id }}</span>
                <span class="nm">{{ $emp->full_name }}</span>
            </div>
            <table class="kv">
                <tr>
                    <td class="k">Brand</td><td class="v">{{ $emp->brand?->name }}</td>
                    <td class="k">Jabatan</td><td class="v">{{ $emp->position?->name }}</td>
                </tr>
                <tr>
                    <td class="k">Divisi</td><td class="v">{{ $emp->division?->name }}</td>
                    <td class="k">Lokasi Kerja</td><td class="v">{{ $emp->location?->name }}</td>
                </tr>
                <tr>
                    <td class="k">Tanggal Bergabung</td><td class="v">{{ $emp->joined_date->format('d M Y') }}</td>
                    <td class="k">Status</td><td class="v">{{ $emp->statusLabel() }}</td>
                </tr>
                @if ($emp->deactivated_at)
                    <tr>
                        <td class="k">Dinonaktifkan Pada</td>
                        <td class="v" colspan="3">{{ $emp->deactivated_at->format('d M Y H:i') }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @empty
        <p class="empty">Tidak ada data untuk laporan ini.</p>
    @endforelse

    <div class="footer">
        JOJO GROUP MANAGEMENT &mdash; Laporan dibuat otomatis oleh Sistem HR Internal pada {{ $generatedAt->format('d/m/Y H:i') }}
    </div>
</body>
</html>