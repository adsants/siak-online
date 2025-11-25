@extends('layouts.app') {{-- ganti sesuai layoutmu --}}
@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-1">Periksa kembali isian berikut:</div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Profil Saya</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                             <div class="col-md-12">
                                <label class="form-label d-block">Foto Profil</label>
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Wrapper agar ukuran selalu konsisten --}}
                                    <div class="profile-photo-wrap">
                                    <img src="{{ $user->photo ? asset('storage/'.$user->photo) : 'https://via.placeholder.com/120' }}" alt="Foto Profil" class="img-thumbnail" style="width:160px;aspect-ratio:1/1;object-fit:cover;object-position:center;border-radius:12px;" />
                                    </div>

                                    <div class="flex-grow-1 ml-2">
                                    <input type="file" name="photo" class="form-control" accept="image/*">
                                    <div class="form-text">jpg/jpeg/png/webp, maks 2MB.</div>
                                    </div>
                                </div>
                                </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Username</label>
                                <input disabled value="{{ old('email', $user->email) }}" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Umur</label>
                                <input disabled value="{{ old('umur', $user->umur) }}" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $user->pekerjaan) }}" class="form-control">
                            </div>

                            <div class="col-12"><hr></div>
                            <!--
                            <div class="col-md-6">
                                <label class="form-label">Password Baru (opsional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ganti">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                            -->
                            <div class="col-12 mt-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary ml-2">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div> <!-- col -->
    </div> <!-- row -->
</div>
@endsection
