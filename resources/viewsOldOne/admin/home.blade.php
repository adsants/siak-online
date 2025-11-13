@extends('layouts.app')
 
@section('content')

            <div class="card">
                <div class="card-header">Dashboard {{ $user->name }}</div>
 
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
 
                    <p><strong>Selamat datang {{ $user->name }}!</strong> Anda telah melakukan login sebagai {{ $user->role }}</p>
                </div>
            </div>
@endsection