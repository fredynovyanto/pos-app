@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-center mt-3">
                    <h1 class="font-weight-bold">Selamat Datang</h1>
                    <h2>Anda login sebagai KASIR</h2>
                    <br>
                    <a href="{{ route('transactions.create') }}" class="btn btn-success">Transaksi Baru</a>
                    <br><br><br>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection