@extends('../../layouts.app')

@section('content')


@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif

<style>
    img{
        max-width: 100%;
        height: auto;
    }
</style>
<div class="card card-default">
    
    <div class="card-header">
        Data Soal BerGambar
    </div>
    <div class="card-header">
        <form class="form-inline">
            <div class="form-group mr-1">
                <input class="form-control" type="text" name="q" value="{{ $q}}" placeholder="Pencarian... Enter" />
            </div>
            <div class="form-group mr-1">
                <a class="btn btn-primary" href="{{ url('soal/kecermatan') }}">Refresh</a>
            </div>
            <div class="form-group mr-1">
                <a class="btn btn-primary" href="{{ url('soal/create-kecermatan') }}">Tambah</a>
            </div>
            <div class="form-group mr-1 text-right">
                <a class="btn btn-success" href="{{ url('soal/json-file-download') }}">Export</a>
                &nbsp;
                <a class="btn btn-warning" href="{{ url('soal/import-kecermatan') }}">Import</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        {!! $tableData !!}
        <br>
        <br>
        <div class="d-flex justify-content-center">
        {{ $pagianate->links() }}
    </div>
    </div>
</div>


<script>
    function gantiSoal(){
        if($('#type_soal').val() == 'gambar'){
            location.href="{{URL::to('soal/kecermatan')}}";
        }
        else if($('#type_soal').val() == 'noGambar'){            
            location.href="{{URL::to('soal/kecermatan')}}";
        }
        else{

        }
    }
</script>
@endsection