@extends('layouts.master')

@section('title')
    Pengaturan
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">@yield('title')</h4>
            </div>
            <form action="{{ route('settings.update') }}" method="post" class="form-setting" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="alert alert-success align-items-center alert-dismissible" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <span class="material-icons d-inline mr-2">done</span> Perubahan berhasil disimpan
                    </div>

                    <div class="form-group mt-3">
                        <label for="company">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="company" name="company" required autofocus>
                    </div>
                    <div class="form-group mt-3">
                        <label for="address">Alamat</label>
                        <textarea name="address" class="form-control" id="address" rows="3" required></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="phone">Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="path_logo">Logo Perusahaan</label>
                        <input style="position: static; opacity: 100;" type="file" name="path_logo" class="form-control" id="path_logo" onchange="preview('.tampil-logo', this.files[0])" placeholder="Logo">
                        <br>
                        <div class="tampil-logo"></div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="tipe_nota">Tipe Nota</label>
                        <select name="tipe_nota" class="form-control" id="tipe_nota" required>
                            <option value="1">Nota Kecil</option>
                            <option value="2">Nota Besar</option>
                        </select>
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
            showData();
            $('.form-setting').on('submit', function (e) {
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('.form-setting').attr('action'),
                        type: $('.form-setting').attr('method'),
                        data: new FormData($('.form-setting')[0]),
                        async: false,
                        processData: false,
                        contentType: false
                    })
                    .done(response => {
                        showData();
                        $('.alert').fadeIn();
                        setTimeout(() => {
                            $('.alert').fadeOut();
                        }, 3000);
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
                }
            });
        });
        function showData() {
            $.get('{{ route('settings.show') }}')
                .done(response => {
                    $('[name=company]').val(response.company);
                    $('[name=phone]').val(response.phone);
                    $('[name=address]').val(response.address);
                    $('[name=tipe_nota]').val(response.tipe_nota);
                    $('title').text(response.company + ' - Pengaturan');
                    
                    let words = response.company.split(' ');
                    let word  = '';
                    words.forEach(w => {
                        word += w.charAt(0);
                    });
                    $('.logo-mini').text(word);
                    $('.logo-lg').text(response.company);
                    $('.tampil-logo').html(`<img src="{{ url('/') }}${response.path_logo}" width="200">`);
                    $('[rel=icon]').attr('href', `{{ url('/') }}/${response.path_logo}`);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }
    </script>
@endpush