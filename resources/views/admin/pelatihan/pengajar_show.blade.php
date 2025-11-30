@extends('../../layouts.app')

@section('content')

@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif

@if(session('error'))
<p class="alert alert-warning">{{ session('error') }}</p>
@endif

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="Peserta">
    <a href="{{url('pelatihan/peserta').'/'.$data_pelatihan->id }}">
    <button class="nav-link " id="home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="home" aria-selected="true">Peserta</button>
    </a>
  </li>
  <li class="nav-item" role="Pengajar">
    <a href="{{url('pelatihan/pengajar').'/'.$data_pelatihan->id }}">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="home" aria-selected="true">Pengajar</button>
    </a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active">
        <div class="card card-default p-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        @if($errors->any())
                        @foreach($errors->all() as $err)
                        <p class="alert alert-danger">{{ $err }}</p>
                        @endforeach
                        @endif
                        <form action="{{url('pelatihan/peserta_create').'/'.$data_pelatihan->id }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Nama Pelatihan <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" disabled name="name" value="{{ $data_pelatihan->name }}" />
                                <input class="form-control" type="hidden" name="jenis" value="pengajar" />
                            </div>
                            <div class="form-group" id='listPeserta'>

                                <select class="form-control selectpicker" data-live-search="true" name="user_id" id="user_id" required>
                                    <option value="">Select an option</option>
                                    @foreach($listPeserta as $list)
                                        <option value="{{ $list->id }}">{{  $list->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <button class="btn btn-primary">Simpan</button>
                                <a class="btn btn-danger" href="{{ url('pelatihan') }}">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php $i = 1;?>
                    @foreach($rows as $row)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $row->name }}</td>
                        <td>
                            <form method="POST" action="{{ url('pelatihan/peserta_delete/' . $row->id . '/pengajar') }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus Data?')">Hapus</button>
                            </form>

                        </td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </table>
                <br>
                <br>
                <div class="d-flex justify-content-center">
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
