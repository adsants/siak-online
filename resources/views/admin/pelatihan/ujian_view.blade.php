@extends('../../layouts.app')

@section('content')


@if(session('success'))  <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))    <div class="alert alert-danger">{{ session('error') }}</div> @endif
@if(session('info'))     <div class="alert alert-info">{{ session('info') }}</div> @endif

@if(session('alert'))
<p class="alert alert-warning">{{ session('alert') }}</p>
@endif


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/ujian-create').'/'.$data_pelatihan->id }}">
    <button class="nav-link " id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Form Tambah Ujian</button>
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/ujian').'/'.$data_pelatihan->id }}">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Data Ujian</button>
    </a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
<div class="card">

    <div class="card-header">
        Daftar Ujian di <b>{{$data_pelatihan->name}}</b>

    </div>
    <div class="card-body p-1 table-responsive">
        <table class="table table-bordered table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Token</th>
                    <th>Tgl </th>
                    <th>Status</th>
                    <th>Soal Ujian</th>
                </tr>
            </thead>

            <?php
                if(count($rows)==0){
              echo "
            <tr>
                    <td colspan='6'>
                   Tidak ada Data Ujian.
                    </td>
                </tr>";
            }
            ?>

            <?php $no = 1 ?>
            @foreach($rows as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->token }}</td>
                <td>{{ $row->tgl_ujian }}</td>
                <td>

                        @if( $row->status == 1)
                           Aktif
                        @else
                           Tidak Aktif
                        @endif


                </td>
                <td>
                    <a class="btn btn-sm btn-warning" href="{{ url('proses-pelatihan/ujian-edit', $row->id ) }}">Edit</a>
                    <a class="btn btn-sm btn-primary" href="{{ url('ujian-soal/show', $row->id ) }}">Soal Ujian</a>
                    <a class="btn btn-sm btn-success" href="{{ url('hasil-ujian/peserta', $row->id ) }}">Hasil Ujian</a>


                  <form method="POST" action="{{ url('proses-pelatihan/ujian-delete/' . $row->id . '/' . $data_pelatihan->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin Hapus Data Ujian ?')">Hapus</button>
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
</div>
</div>
@endsection
