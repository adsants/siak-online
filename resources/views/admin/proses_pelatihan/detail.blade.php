@extends('../../layouts.app')

@section('content')

@if(session('success'))
<p class="alert alert-success">{{ session('success') }}</p>
@endif
<div class="card card-default">
   
  <div class="card-header d-flex justify-content-between align-items-center">
        <span>Detail Absensi : {{$data_pelatihan->name}}</span>
        <button class="btn btn-primary btn-sm"  onclick="exportTableToExcel('tableAbsensi', 'Data Absensi  {{$data_pelatihan->name}}')">Cetak Excel</button>
    </div>

    <div class="card-body ">

        <table id="tableAbsensi" style="border-collapse:collapse" border="1px" class="table table-bordered">
            {!! $tableHasil !!}
    
        </table>
    </div>
    </div>


<div class="card card-default mt-2">
   
  <div class="card-header d-flex justify-content-between align-items-center">
        <span>Detail Ujian : {{$data_pelatihan->name}}</span>
        <button class="btn btn-primary btn-sm"  onclick="exportTableToExcel('tableUjian', 'Data Ujian   {{$data_pelatihan->name}}')">Cetak Excel</button>
    </div>

    <div class="card-body ">
        <table id="tableUjian" style="border-collapse:collapse" border="1px" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tgl Pengerjaan</th>
                    <th>Jawaban Benar</th>
                    <th>Jawaban Salah</th>
                    <th>Nilai</th>
                </tr>
            </thead>
           {!!$textHtmlShow!!}
        </table>
    </div>
</div>



<script>
function exportTableToExcel(tableID, filename = ''){
    var table = document.getElementById(tableID);
    var wb = XLSX.utils.table_to_book(table, {sheet:"Sheet1"});
    XLSX.writeFile(wb, filename ? filename + '.xlsx' : 'export.xlsx');
}
</script>

@endsection