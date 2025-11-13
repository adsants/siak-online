@extends('../../layouts.app')

@section('content')


@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif
<div class="card card-default">
    
    <div class="card-header">
        Data Soal Non Gambar
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form >
                    <div class="form-group">
                        <label>Type Soal <span class="text-danger">*</span></label>
                        <select class="form-control" required name="type_soal" id="type_soal" onchange="gantiSoal()">
                            <option value="">Silahkan Pilih</option>
                            <option selected value="noGambar">Soal Non gambar</option>
                            <option value="gambar">Soal Bergambar</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-header">
        <form class="form-inline">
            <div class="form-group mr-1">
                <input class="form-control" type="text" name="q" value="{{ $q}}" placeholder="Pencarian... Enter" />
            </div>
            <div class="form-group mr-1">
                <a class="btn btn-primary" href="{{ url('soal') }}">Refresh</a>
            </div>
            <div class="form-group mr-1">
                <a class="btn btn-primary" href="{{ url('soal/create') }}">Tambah</a>
            </div>
            <div class="form-group mr-1 text-right">
                <a class="btn btn-success" href="{{ url('export-soal') }}">Export</a>
                &nbsp;
                <a class="btn btn-warning" href="{{ url('import-soal-view') }}">Import</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Jenis Soal</th>
                    <th>Soal</th>
                    <th>Jawaban Benar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php $no = 1 ?>
            @foreach($rows as $row)
            <?php
            ?>
            <tr>
                <td>{{ $row->jenis_soal}}</td>
                <td>{{ $row->data_soal}}</td>
                <td>{{ $row->jawaban_benar}}</td>
                <td>
                    <a class="btn btn-sm btn-warning" href="{{ url('soal/edit', $row->id ) }}">Ubah</a>
                    
                    <form method="POST" action="{{ url('soal/delete', $row->id) }}" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus Data?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        <br>
        <br>
        <div class="d-flex justify-content-center">
        {{ $rows->links() }}
    </div>
    </div>
</div>


<script>
    function gantiSoal(){
        if($('#type_soal').val() == 'gambar'){
            location.href="{{URL::to('soal/gambar')}}";
        }
        else if($('#type_soal').val() == 'noGambar'){            
            location.href="{{URL::to('soal/nogambar')}}";
        }
        else{

        }
    }
</script>
@endsection