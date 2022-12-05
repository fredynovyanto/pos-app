@extends('layouts.master')

@section('title')
    Edit Profile
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">@yield('title')</h4>
            </div>
            <form action="{{ route('users.update_profile') }}" method="post" class="form-profil" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="alert alert-success align-items-center alert-dismissible" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <span class="material-icons d-inline mr-2">done</span> Perubahan berhasil disimpan
                    </div>

                    <div class="form-group mt-3">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required autofocus value="{{ $profile->name }}">
                    </div>
                    <div class="form-group mt-3">
                        <label for="foto">Profil</label>
                        <input style="position: static; opacity: 100;" type="file" name="foto" class="form-control" id="foto" onchange="preview('.tampil-foto', this.files[0])" placeholder="Foto">
                        <br>
                        <div class="tampil-foto">
                            <img src="{{ url($profile->foto ?? '/img/user.jpg') }}" width="200">
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="old_password">Password Lama</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" minlength="8">
                    </div>
                    <div class="form-group mt-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="8">
                    </div>
                    <div class="form-group mt-3">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="8">
                    </div>
                </div>
                <div class="card-footer pull-right">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#old_password').on('keyup', function () {
                if ($(this).val() != "") $('#password, #password_confirmation').attr('required', true);
                else $('#password, #password_confirmation').attr('required', false);
            });
            $('.form-profil').on('submit', function (e) {
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('.form-profil').attr('action'),
                        type: $('.form-profil').attr('method'),
                        data: new FormData($('.form-profil')[0]),
                        async: false,
                        processData: false,
                        contentType: false
                    })
                    .done(response => {
                        $('[name=name]').val(response.name);
                        $('.tampil-foto').html(`<img src="{{ url('/') }}${response.foto}" width="200">`);
                        $('.img-profil').attr('src', `{{ url('/') }}/${response.foto}`);
                        $('.alert').fadeIn();
                        setTimeout(() => {
                            $('.alert').fadeOut();
                            $('#old_password, #password, #password_confirmation').val('');
                        }, 3000);
                    })
                    .fail(errors => {
                        if (errors.status == 422) {
                            alert(errors.responseJSON); 
                        } else {
                            alert('Tidak dapat menyimpan data');
                        }
                        return;
                    });
                }
            });
        });
    </script>
@endpush