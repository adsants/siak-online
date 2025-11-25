@extends('../../layouts.app')

@section('content')


@if(session('success'))  <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))    <div class="alert alert-danger">{{ session('error') }}</div> @endif
@if(session('info'))     <div class="alert alert-info">{{ session('info') }}</div> @endif
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/ujian-create').'/'.$data_pelatihan->id }}">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Form Edit Ujian</button>
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/ujian').'/'.$data_pelatihan->id }}">
    <button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Data Ujian</button>
    </a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
<div class="card">
    <div class="card-header">
        Edit Data Ujian di <b>{{$data_pelatihan->name}}</b>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif


                <form action="{{url('proses-pelatihan/ujian-update', $row->id) }}" method="POST">
                   @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Nama Ujian <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" value="{{ old('name', $row->name) }}" />
                    </div>

                    <div class="form-group">
                        <label>Tgl Ujian <span class="text-danger">*</span></label>
                        <input class="form-control  w-25" type="date" name="tgl_ujian" value="{{ date('Y-m-d', strtotime($row->tgl_ujian)) }}" />
                    </div>

                    <div class="form-group">
                        <label>status <span class="text-danger">*</span></label>
                        <select class="form-control w-25" name="status">
                        @foreach($status as $key => $val)
                            @if($key==old('status',$row->status))
                                <option value="{{ $key }}" selected>{{ $val }}</option>
                            @else
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>



                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('ujian') }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<link href="{{ asset('/dist/tokenize2.min.css') }}" rel="stylesheet">

<script src="{{ asset('/dist/tokenize2.min.js') }}"></script>

<script>$('.tokenize-demo').tokenize2();</script>
@endsection
