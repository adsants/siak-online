@extends('../../layouts.app')

@section('content')


@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif


<div class="card card-default">
    <div class="card-header">
        Peserta Ujian : {{$data_ujian->name}}
    </div>
    <div class="card-header">
        <form class="form-inline">
            <!--
            <div class="form-group mr-1">
                <a class="btn btn-primary" href="{{ url('ujian-user/create/').'/'.$data_ujian->id }}">Tambah</a>
            </div>
            -->
            <div class="form-group mr-1 text-right">               
                <a class="btn btn-danger" href="{{ url('ujian') }}">Kembali</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php $no = 1 ?>
            @foreach($rows as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->name }}</td>
                <td>   <form method="POST" action="{{ url('ujian-user/delete', $row->id) }}" style="display: inline-block;">
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
    </div>
    </div>
</div>
<input id="myInput" style="display:none">
<script>
    function copyText(ujianName,tglUjian,token){
        var textCopy = "Silahkan Akses https://ujian.smartpsisurabaya.com/token untuk Ujian *"+ujianName+"*. Ujian akan dimulai pada Tanggal *"+tglUjian+"*. Token anda adalah *"+token+"*";
        $('#myInput').val(textCopy);

        var copyText = document.getElementById("myInput");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);

        /* Alert the copied text */
        alert("Berhasil copt Text");
    }
    
</script>
@endsection