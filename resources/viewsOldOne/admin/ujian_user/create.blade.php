@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Tambah Data Peserta Ujian
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{url('ujian-user/store').'/'.$data_ujian->id }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama Ujian <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" disabled name="name" value="{{ $data_ujian->name }}" />
                    </div>
                    <div class="form-group">
                        <label>Nama Peserta <span class="text-danger">*</span></label>
                        <input type="text" autocomplete="off" id="search" name="search" placeholder="Search" class="form-control" />
                        <input type="hidden" id="user_id" name="user_id"/>
                        <input type="hidden" id="ujian_id" name="ujian_id" value="{{$data_ujian->id}}"/>
                    </div>
                
                    <div class="form-group">
                        <button class="btn btn-primary">Simpan</button>
                        <a class="btn btn-danger" href="{{ url('ujian-user/show').'/'.$data_ujian->id }}">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
            var route = "{{ url('autocomplete-search') }}";

            $('#search').typeahead({
                source: function (query, process) {
                    return $.get(route, {
                        query: query,
                        ujian_id : {{$data_ujian->id}}
                    }, function (data) {
                        return process(data);
                    });
                } ,
                afterSelect: function(item) {
                    $('#user_id').val(item.id); 
                }
            });

            
        </script>
@endsection