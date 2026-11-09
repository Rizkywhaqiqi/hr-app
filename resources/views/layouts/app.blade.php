<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'HR Internal')</title>
<style>
:root{--navy:#0f2b46;--navy-light:#163a5e;--accent:#f2711c;--accent-dark:#d95f0e;
--bg:#f4f6f9;--card:#fff;--border:#e3e8ef;--text:#22303f;--muted:#6c7a8a;--green:#21ba45;--red:#db2828;}
*{box-sizing:border-box;}
body{margin:0;font-family:"Segoe UI",Tahoma,Arial,sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;}
a{color:var(--accent);text-decoration:none;}a:hover{color:var(--accent-dark);}
.sidebar{width:240px;background:linear-gradient(180deg,var(--navy),var(--navy-light));color:#dbe4ee;flex-shrink:0;display:flex;flex-direction:column;}
.sidebar .brand{padding:22px 20px;font-size:18px;font-weight:700;color:#fff;border-bottom:1px solid rgba(255,255,255,.08);}
.sidebar .brand span{color:var(--accent);}
.sidebar nav a{display:block;padding:13px 22px;color:#c9d6e4;border-left:3px solid transparent;transition:.15s;}
.sidebar nav a:hover{background:rgba(255,255,255,.06);color:#fff;}
.sidebar nav a.active{background:rgba(255,255,255,.1);color:#fff;border-left-color:var(--accent);font-weight:600;}
.sidebar nav .nav-group-toggle{display:flex;justify-content:space-between;align-items:center;cursor:pointer;}
.sidebar nav .nav-group-toggle .caret{font-size:10px;transition:transform .2s;}
.sidebar nav .nav-group-toggle.open .caret{transform:rotate(180deg);}
.nav-sub{max-height:0;overflow:hidden;transition:max-height .25s ease;}
.nav-sub.open{max-height:420px;}
.sidebar .nav-sub a{padding:11px 22px 11px 46px;font-size:13.5px;}
.sidebar .foot{margin-top:auto;padding:16px 22px;font-size:12px;color:#8197ad;}
.main{flex:1;display:flex;flex-direction:column;min-width:0;}
.topbar{background:var(--card);border-bottom:1px solid var(--border);padding:16px 28px;display:flex;align-items:center;gap:12px;}
.topbar h1{margin:0;font-size:20px;}
.topbar-user{margin-left:auto;display:flex;align-items:center;gap:10px;}
.topbar-user .uname{font-size:13px;color:var(--muted);font-weight:600;}
.btn-logout{background:#fff;border:1px solid #cfd8e3;color:var(--navy);border-radius:8px;padding:8px 14px;font-size:13px;cursor:pointer;}
.btn-logout:hover{border-color:var(--navy);}
.content{padding:26px 28px;}
.alert{padding:13px 18px;border-radius:8px;margin-bottom:18px;border:1px solid transparent;font-weight:500;}
.alert-success{background:#e7f7ea;color:#17642a;border-color:#b7e5c0;}
.alert-error{background:#fdeaea;color:#a11d1d;border-color:#f3c1c1;}
.card{background:var(--card);border:1px solid var(--border);border-radius:10px;padding:20px;margin-bottom:20px;}
.card h2{margin:0 0 14px;font-size:16px;color:var(--navy);}
.grid{display:grid;gap:16px;}.grid-4{grid-template-columns:repeat(4,1fr);}.grid-2{grid-template-columns:repeat(2,1fr);}
.stat{background:var(--card);border:1px solid var(--border);border-left:4px solid var(--accent);border-radius:10px;padding:18px 20px;}
.stat .num{font-size:30px;font-weight:800;color:var(--navy);}
.stat .lbl{color:var(--muted);font-size:13px;}
.stat.green{border-left-color:var(--green);}.stat.red{border-left-color:var(--red);}.stat.blue{border-left-color:var(--navy-light);}
.sidebar{overflow-y:auto;}
.menu-toggle{display:none;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid var(--border);border-radius:10px;background:#fff;cursor:pointer;font-size:20px;line-height:1;color:var(--navy);}
.menu-toggle:active{background:#eef2f7;}
.backdrop{display:none;position:fixed;inset:0;background:rgba(15,43,70,.5);z-index:55;}
.table-wrap{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;}
.table-wrap table{min-width:640px;}
a.stat{display:block;color:inherit;transition:transform .15s,box-shadow .15s,border-color .15s;}
a.stat:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(15,43,70,.12);border-color:#cfd8e3;color:inherit;}
.stat .go{font-size:12px;color:var(--accent);margin-top:6px;font-weight:600;}
.chart-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.donut-wrap{display:flex;gap:22px;align-items:center;flex-wrap:wrap;}
.donut{width:132px;height:132px;border-radius:50%;position:relative;flex-shrink:0;}
.donut::after{content:"";position:absolute;inset:24px;background:var(--card);border-radius:50%;}
.donut-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;}
.donut-center .n{font-size:22px;font-weight:800;color:var(--navy);}
.donut-center .t{font-size:11px;color:var(--muted);}
.legend{display:flex;flex-direction:column;gap:10px;font-size:13px;}
.legend .dot{display:inline-block;width:12px;height:12px;border-radius:3px;margin-right:8px;vertical-align:-1px;}
.bar-row{display:grid;grid-template-columns:110px 1fr 44px;gap:10px;align-items:center;padding:7px 8px;border-radius:8px;color:var(--text);transition:background .15s;}
.bar-row:hover{background:#f4f6f9;color:var(--text);}
.bar-label{font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.bar-track{background:#eef2f7;border-radius:20px;height:14px;overflow:hidden;}
.bar-fill{display:block;height:100%;border-radius:20px;background:linear-gradient(90deg,var(--navy-light),var(--accent));transition:width .6s ease;}
.bar-value{text-align:right;font-weight:700;color:var(--navy);font-size:13px;}
@media(max-width:1024px){
  .sidebar{position:fixed;top:0;left:0;height:100vh;width:250px;max-width:82vw;transform:translateX(-100%);transition:transform .25s ease;z-index:60;box-shadow:2px 0 18px rgba(0,0,0,.25);}
  body.nav-open .sidebar{transform:translateX(0);}
  body.nav-open .backdrop{display:block;}
  .menu-toggle{display:inline-flex;}
  .grid-4{grid-template-columns:repeat(2,1fr);}
}
@media(max-width:640px){
  .topbar{padding:12px 16px;}
  .topbar h1{font-size:18px;}
  .content{padding:16px;}
  .grid-4,.grid-2,.chart-grid{grid-template-columns:1fr;}
  .stat .num{font-size:26px;}
  .form-row{grid-template-columns:1fr;}
  .filter-bar .form-group{min-width:100%;}
  .bar-row{grid-template-columns:92px 1fr 34px;}
}
table{width:100%;border-collapse:collapse;background:var(--card);}
th,td{padding:11px 14px;text-align:left;border-bottom:1px solid var(--border);font-size:14px;}
th{background:#eef2f7;color:var(--navy);font-weight:600;white-space:nowrap;}
tr:hover td{background:#f7f9fc;}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;}
.badge.active{background:var(--green);color:#fff;}.badge.inactive{background:#e4e7eb;color:#5b6673;}
.btn{display:inline-block;padding:9px 16px;border:1px solid transparent;border-radius:8px;cursor:pointer;font-size:14px;line-height:1.2;transition:.15s;}
.btn-primary{background:var(--accent);color:#fff;}
.btn-primary:hover{background:var(--accent-dark);color:#fff;}
.btn-outline{background:#fff;color:var(--navy);border-color:#cfd8e3;}.btn-outline:hover{border-color:var(--navy);}
.btn-sm{padding:6px 12px;font-size:13px;}
.btn-green{background:var(--green);color:#fff;}
.btn-red{background:#fff;color:var(--red);border-color:#f0c0c0;}.btn-red:hover{background:var(--red);color:#fff;}
.form-group{margin-bottom:16px;}
.form-group label{display:block;font-weight:600;margin-bottom:6px;font-size:14px;}
.form-group input,.form-group select{width:100%;padding:10px 12px;border:1px solid #cfd8e3;border-radius:8px;font-size:14px;background:#fff;}
.form-group input:focus,.form-group select:focus{outline:2px solid var(--accent);border-color:var(--accent);}
.form-row{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
.error{color:var(--red);font-size:13px;margin-top:4px;}
.help{color:var(--muted);font-size:12px;margin-top:4px;}
.checkbox-line{display:flex;align-items:center;gap:10px;}.checkbox-line input{width:auto;}
.actions{display:flex;gap:8px;align-items:center;}
.filter-bar{display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;}
.filter-bar .form-group{margin-bottom:0;min-width:150px;}
.empty{text-align:center;color:var(--muted);padding:30px;}
.flex{display:flex;gap:12px;align-items:center;}.muted{color:var(--muted);}.mt-2{margin-top:16px;}
.kv{display:grid;grid-template-columns:180px 1fr;row-gap:12px;font-size:14px;}
.kv dt{color:var(--muted);}.kv dd{margin:0;font-weight:500;}
.pager{display:flex;gap:8px;align-items:center;justify-content:center;margin-top:18px;flex-wrap:wrap;}
.pager a,.pager span{display:inline-block;padding:7px 12px;border:1px solid #cfd8e3;border-radius:6px;font-size:13px;color:var(--navy);background:#fff;}
.pager a:hover{border-color:var(--accent);}
.pager .current{background:var(--navy);color:#fff;border-color:var(--navy);}
.pager .disabled{opacity:.4;pointer-events:none;}
</style>
</head>
<body>
<aside class="sidebar" id="sidebar">
    <div class="brand">HR <span>Internal</span></div>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.index','employees.show','employees.edit') ? 'active' : '' }}">Karyawan</a>
        <a href="{{ route('employees.create') }}" class="{{ request()->routeIs('employees.create') ? 'active' : '' }}">Tambah Karyawan</a>
        @php $masterActive = request()->routeIs('brands.*', 'divisions.*', 'locations.*', 'positions.*'); @endphp
        <div class="nav-group">
            <a href="#" id="masterToggle" role="button" aria-controls="masterSub"
               aria-expanded="{{ $masterActive ? 'true' : 'false' }}"
               class="nav-group-toggle {{ $masterActive ? 'active open' : '' }}">
                Master Data <span class="caret">&#9662;</span>
            </a>
            <div class="nav-sub {{ $masterActive ? 'open' : '' }}" id="masterSub">
                <a href="{{ route('brands.index') }}" class="{{ request()->routeIs('brands.*') ? 'active' : '' }}">Brand</a>
                <a href="{{ route('divisions.index') }}" class="{{ request()->routeIs('divisions.*') ? 'active' : '' }}">Divisi</a>
                <a href="{{ route('locations.index') }}" class="{{ request()->routeIs('locations.*') ? 'active' : '' }}">Lokasi</a>
                <a href="{{ route('positions.index') }}" class="{{ request()->routeIs('positions.*') ? 'active' : '' }}">Jabatan</a>
            </div>
        </div>
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>
    </nav>
    <div class="foot">Practical Test IT by rizkywhaqiqi</div>
</aside>

<div class="main">
    <header class="topbar">
        <button type="button" class="menu-toggle" id="menuToggle" aria-label="Buka menu" aria-controls="sidebar">&#9776;</button>
        <h1>@yield('title', 'HR Internal')</h1>
        <div class="topbar-user">
            <span class="uname">{{ auth()->user()?->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </header>
    <div class="content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>
<div class="backdrop" id="navBackdrop"></div>
<script>
(function () {
    var body = document.body;
    var toggle = document.getElementById('menuToggle');
    var backdrop = document.getElementById('navBackdrop');
    if (toggle) {
        toggle.addEventListener('click', function () { body.classList.toggle('nav-open'); });
    }
    if (backdrop) {
        backdrop.addEventListener('click', function () { body.classList.remove('nav-open'); });
    }
    // Toggle grup "Master Data"
    var masterToggle = document.getElementById('masterToggle');
    var masterSub = document.getElementById('masterSub');
    if (masterToggle && masterSub) {
        masterToggle.addEventListener('click', function (e) {
            e.preventDefault();
            masterSub.classList.toggle('open');
            masterToggle.classList.toggle('open');
            masterToggle.setAttribute('aria-expanded', masterSub.classList.contains('open') ? 'true' : 'false');
        });
    }

    // Tutup drawer mobile saat memilih menu (kecuali tombol grup Master Data)
    document.querySelectorAll('.sidebar nav a:not(.nav-group-toggle)').forEach(function (a) {
        a.addEventListener('click', function () { body.classList.remove('nav-open'); });
    });
})();
</script>
</body>
</html>