@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Import Data User
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6">
                @if($errors->any())
                @foreach($errors->all() as $err)
                <p class="alert alert-danger">{{ $err }}</p>
                @endforeach
                @endif
                <form action="{{ route('import-user') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <input type="file" name="file" class="form-control">
                        <p class="form-text">Pastikan Username tidak ada yang kembar atau sama dengan Data di Master User.</p>

                    <a target="_BLANK" href="{{ asset('/documents/user-example.xlsx') }}">contoh file excel</a>
                    <br>
                    <br>
                    <button class="btn btn-success">Import User Data</button>
                    <a class="btn btn-danger" href="{{ url('user-admin') }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
