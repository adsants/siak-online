<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Scripts -->
<script src="{{ asset('/js/app.js') }}" defer></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<!-- Styles -->
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

<script src="{{ asset('/js/jquery.js') }}"></script>
<script src="{{ asset('/js/bootstrap3-typeahead.min.js') }}"></script>
<script src="{{ asset('/ckeditor/ckeditor.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

</head>
<body>
<div id="app">
<nav class="navbar navbar-expand-md navbar-light shadow-sm">
    <div class="container">
        <!--<img  style="max-height:20px; max-width: 100%;display: block;"src="{{ asset('/images/logo.png') }}" height="20px" class="mr-2">
        <a class="navbar-brand mt-1" href="{{ url('/home') }}">
        SmartPSI Surabaya
        </a>-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            @guest

            @else
                @if (Auth::user()->role == 'admin')
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Master Data
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php $menuUser = \cekmenuadmin::hasilcek(Auth::user()->id, 'user');?>
                            @if ($menuUser)
                                <a class="dropdown-item" href="{{url('user-admin')}}">User Admin</a>
                            @endif
                            @if ($menuUser)
                                <a class="dropdown-item" href="{{url('user-pengajar')}}">User Pengajar</a>
                            @endif
                            @if ($menuUser)
                                <a class="dropdown-item" href="{{url('user-siswa')}}">User Siswa</a>                            
                            @endif
                            
                            <?php $menuMasterPelatihan = \cekmenuadmin::hasilcek(Auth::user()->id, 'master_data_pelatihan');?>
                            @if ($menuMasterPelatihan)
                                <a class="dropdown-item" href="{{url('pelatihan')}}">Pelatihan</a>
                            @endif
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Master Ujian
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            
                            <?php $menuSoal = \cekmenuadmin::hasilcek(Auth::user()->id, 'text_info');?>
                            @if ($menuSoal)
                                <a class="dropdown-item" href="{{url('text-info')}}">Text Info Simulasi</a>
                            @endif

                            <?php $menuSoal = \cekmenuadmin::hasilcek(Auth::user()->id, 'jenis_soal');?>
                            @if ($menuSoal)
                                <a class="dropdown-item" href="{{url('jenis-soal')}}">Jenis Soal</a>
                            @endif
                            
                            <?php $menuSoal = \cekmenuadmin::hasilcek(Auth::user()->id, 'soal');?>
                            @if ($menuSoal)
                                <a class="dropdown-item" href="{{url('soal')}}">Bank Soal</a>
                            @endif                                   

                            <?php $menuSoal = \cekmenuadmin::hasilcek(Auth::user()->id, 'soal');?>
                            @if ($menuSoal)
                                <a class="dropdown-item" href="{{url('soal/kecermatan')}}">Bank Soal Kecermatan</a>
                            @endif
                            
                        </div>
                    </li>
                    <!--
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Ujian
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            
                            <?php $menuUjian = \cekmenuadmin::hasilcek(Auth::user()->id, 'ujian');?>
                            @if ($menuUjian)
                                <a class="dropdown-item" href="{{url('ujian')}}">Ujian</a>
                            @endif
                            
                            <?php $hasilUjian = \cekmenuadmin::hasilcek(Auth::user()->id, 'hasil_ujian');?>
                            @if ($hasilUjian)
                                <a class="dropdown-item" href="{{url('hasil-ujian')}}">Hasil Ujian</a>
                            @endif
                            
                            <?php $grafik = \cekmenuadmin::hasilcek(Auth::user()->id, 'grafik');?>
                            @if ($grafik)
                                <a class="dropdown-item" href="{{url('grafik-nilai-ujian')}}">Grafik Nilai Ujian</a>
                            @endif
                            
                        </div>
                    </li>
                    
                    -->
                    <?php $menuUjian = \cekmenuadmin::hasilcek(Auth::user()->id, 'proses_pelatihan');?>
                    @if ($menuUjian)
                        <li class="nav-item">
                            <a id="navbarDropdown" class="nav-link" href="{{url('proses-pelatihan')}}">Proses Pelatihan</a>                              
                        </li>
                    @endif
                        
                </ul>
                @elseif(Auth::user()->role == 'pengajar')
                <ul class="navbar-nav mr-auto">
                 
                    <li class="nav-item">
                        <a id="navbarDropdown" class="nav-link" href="{{url('proses-pelatihan')}}">Proses Pelatihan</a>                              
                    </li>
                </ul>
                 @else
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('user')}}">Daftar Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('riwayat-pelatihan')}}">Riwayat Pelatihan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('profile')}}">Profil Saya</a>
                    </li>
                </ul>
                @endif
            @endguest

            <ul class="navbar-nav ml-auto">
                @guest
                    @if (Route::has('login')) @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                @yield('content')

            </div>
        </div>
    </div>
</main>
</div>
</body>
</html>
