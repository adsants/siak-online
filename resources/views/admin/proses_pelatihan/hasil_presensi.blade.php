{{-- resources/views/admin/proses_pelatihan/presensi.blade.php --}}
@extends('../../layouts.app')

@section('content')

@if(session('success'))  <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))    <div class="alert alert-danger">{{ session('error') }}</div> @endif
@if(session('info'))     <div class="alert alert-info">{{ session('info') }}</div> @endif
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/presensi').'/'.$data_pelatihan->id }}">
    <button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Form Presensi</button>
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/hasil-presensi').'/'.$data_pelatihan->id }}">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Hasil Presensi</button>
    </a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
<div class="card">
    
    <div class="card-header">
        Hasil Presensi di <b>{{$data_pelatihan->name}}</b>
        
    </div>
  <div class="card-body"> 

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          {!! $tableHasil !!}
        </table>
      </div>

  </div>
</div>
</div>
</div>
@endsection
