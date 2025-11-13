@extends('../../layouts.app')

@section('content')

<div class="card card-default">
    <div class="card-header">
        Tambah Data User
    </div>
    <div class="card-body p-4">
        <div class="row">
        <div class="col-md-6">
            @if($errors->any())
            @foreach($errors->all() as $err)
            <p class="alert alert-danger">{{ $err }}</p>
            @endforeach
            @endif
            <form action="{{ url('user-admin/store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama User <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" />
                </div>
                <div class="form-group">
                    <label>Umur <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="umur" value="{{ old('umur') }}" />
                </div>
                <div class="form-group">
                    <label>Pekerjaan <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" />
                </div>
                <div class="form-group">
                    <label>Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control" type="text" name="alamat">{{ old('alamat') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Username <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="email_form" value="{{ old('email') }}" />
                </div>
                <div class="form-group">
                    <label>Password <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="password_form" />
                </div>
                <div class="form-group">
                    <label>Level <span class="text-danger">*</span></label>
                    <select class="form-control" required name="role" onchange="menuAdmin()" id="role">
                    
                    <option value="">Pilih</option>

                    @foreach($levels as $key => $val)
                    @if($key==old('level'))
                    <option value="{{ $key }}" selected>{{ $val }}</option>
                    @else
                    <option value="{{ $key }}">{{ $val }}</option>
                    @endif
                    @endforeach
                    </select>
                </div>
                <div class="form-group" id="formMenu">
                    <label>Pilih Menu <span class="text-danger">*</span></label>
                   
                    <?php $i=1;?>
                    @foreach($tampilMenu as $menu)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="menu[]" value="{{$menu}}" id="defaultCheck{{$i}}">
                            <label class="form-check-label" for="defaultCheck{{$i}}">
                                {{$menu}}
                            </label>
                    </div>
                    <?php $i++;?>
                    @endforeach
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Simpan</button>
                    <a class="btn btn-danger" href="{{ url('user-admin') }}">Kembali</a>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

<script>
    $('#formMenu').hide();
    function menuAdmin(){
        if($('#role').val() == 'admin'){
            $('#formMenu').show();
        }
        else{
            $('#formMenu').hide();
        }
        
    }
</script>
@endsection