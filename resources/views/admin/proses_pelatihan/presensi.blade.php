{{-- resources/views/admin/proses_pelatihan/presensi.blade.php --}}
@extends('../../layouts.app')

@section('content')

@if(session('success'))  <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))    <div class="alert alert-danger">{{ session('error') }}</div> @endif
@if(session('info'))     <div class="alert alert-info">{{ session('info') }}</div> @endif

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/presensi').'/'.$data_pelatihan->id }}">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Form Presensi</button>
    </a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="{{url('proses-pelatihan/hasil-presensi').'/'.$data_pelatihan->id }}">
    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Hasil Presensi</button>
    </a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
<div class="card">
    
    <div class="card-header">
        Form Absensi di <b>{{$data_pelatihan->name}}</b>
        
    </div>

@if ($sudahAbsen)

    <div class="card-body">
        <div class="alert alert-success" role="alert">
            Hari ini sudah melakukan Absensi
        </div>
    </div>


    @else
    <div class="card-body">
        <h5 class="mb-3">Presensi: {{ $data_pelatihan->name }}</h5>

        <form method="POST" action="{{ route('pelatihan.presensi.proses', $data_pelatihan->id) }}">
            @csrf
            <div class="form-group">
                <label>Modul / Kegiatan yang diajarkan per Hari ini <span class="text-danger">*</span></label>
                <input class="form-control " type="text" name="module_name" value="{{ old('module_name') }}" required />
            </div>
            <div class="form-group">
                <label>Deskripsi (jika diperlukan)</label>
                <textarea class="form-control  "  name="module_deskripsi">{{ old('module_deskripsi') }}</textarea>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Nama</th>
                            <th style="width:180px;">Status</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i = 1; @endphp
                        @foreach($rows as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->name }}</td>
                            <td>
                            <select  class="form-control" name="presensi[{{ $row->user_id }}][jenis]" class="form-select" required>

                            <option value="">-- pilih --</option>
                            <option value="P">Hadir (P)</option>
                            <option value="A">Alpa (A)</option>
                            </select>
                            </td>
                            <td>
                            <input type="text"
                            name="presensi[{{ $row->user_id }}][keterangan]"
                            class="form-control"
                            placeholder="(opsional) keterangan..." maxlength="150">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                Simpan Presensi
                </button>
                &nbsp;
                <a href="{{ url('proses-pelatihan') }}" class="btn btn-outline-secondary">
                Kembali
                </a>
            </div>
        </form>
    </div>

    @endif
</div>            
</div>
</div>
@endsection
