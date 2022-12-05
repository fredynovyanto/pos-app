@extends('layouts.master')

@section('title')
    Transaksi Penjualan
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success d-flex align-items-center">
                    <span class="material-icons d-inline mr-2">done</span>
                    Data Transaksi telah selesai.
                </div>
                    @if ($setting->tipe_nota == 1)
                    <button class="btn btn-warning" onclick="notaKecil('{{ route('transactions.nota_kecil') }}', 'Nota Kecil')">Cetak Ulang Nota</button>
                    @else
                    <button class="btn btn-warning" onclick="notaBesar('{{ route('transactions.nota_besar') }}', 'Nota PDF')">Cetak Ulang Nota</button>
                    @endif
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">Transaksi Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // tambahkan untuk delete cookie innerHeight terlebih dahulu
        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        
        function notaKecil(url, title) {
            popupCenter(url, title, 625, 500);
        }
        function notaBesar(url, title) {
            popupCenter(url, title, 900, 675);
        }
        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
            const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;
            const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
            const systemZoom = width / window.screen.availWidth;
            const left       = (width - w) / 2 / systemZoom + dualScreenLeft
            const top        = (height - h) / 2 / systemZoom + dualScreenTop
            const newWindow  = window.open(url, title, 
            `
                scrollbars=yes,
                width  = ${w / systemZoom}, 
                height = ${h / systemZoom}, 
                top    = ${top}, 
                left   = ${left}
            `
            );
            if (window.focus) newWindow.focus();
        }
    </script>
@endpush