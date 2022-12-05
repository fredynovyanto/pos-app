<div class="sidebar" data-color="purple" data-background-color="white" data-image="{{asset('assets/img/sidebar-1.jpg')}}">
    <div class="logo"><a href="{{route('dashboard')}}" class="simple-text logo-normal">
      {{ $setting->company }}
      </a></div>
    <div class="sidebar-wrapper">
      <ul class="nav">
        <li class="nav-item {{request()->is('/') || request()->is('dashboard') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('dashboard')}}">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
          </a>
        </li>
        <hr>
        @if (auth()->user()->level == 1)
        <li class="nav-item {{request()->is('categories') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('categories.index')}}">
            <i class="material-icons">category</i>
            <p>Kategori</p>
          </a>
        </li>
        <li class="nav-item {{request()->is('products') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('products.index')}}">
            <i class="material-icons">inventory</i>
            <p>Produk</p>
          </a>
        </li>
        <li class="nav-item {{request()->is('suppliers') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('suppliers.index')}}">
            <i class="material-icons">local_shipping</i>
            <p>Supplier</p>
          </a>
        </li>
        <hr>
        <li class="nav-item {{request()->is('spendings') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('spendings.index')}}">
            <i class="material-icons">paid</i>
            <p>Pengeluaran</p>
          </a>
        </li>
        <li class="nav-item {{request()->is('purchases') || request()->is('purchase-details*') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('purchases.index')}}">
            <i class="material-icons">shopping_cart</i>
            <p>Pembelian</p>
          </a>
        </li>
        <li class="nav-item {{request()->is('sales') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('sales.index')}}">
            <i class="material-icons">shopping_cart_checkout</i>
            <p>Penjualan</p>
          </a>
        </li>
        {{-- <li class="nav-item {{request()->is('transactions') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('transactions.index')}}">
            <i class="material-icons">history</i>
            <p>Transaksi Lama</p>
          </a>
        </li> --}}
        <li class="nav-item {{request()->is('transactions') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('transactions.create')}}">
            <i class="material-icons">point_of_sale</i>
            <p>Transaksi</p>
          </a>
        </li>
        <hr>
        <li class="nav-item {{request()->is('laporan') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('laporan.index')}}">
            <i class="material-icons">receipt_long</i>
            <p>Laporan</p>
          </a>
        </li>
        <hr>
        <li class="nav-item {{request()->is('users') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('users.index')}}">
            <i class="material-icons">people</i>
            <p>User</p>
          </a>
        </li>
        <li class="nav-item {{request()->is('settings') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('settings.index')}}">
            <i class="material-icons">settings</i>
            <p>Pengaturan</p>
          </a>
        </li>
        @else
        {{-- <li class="nav-item {{request()->is('transactions') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('transactions.index')}}">
            <i class="material-icons">history</i>
            <p>Transaksi Lama</p>
          </a>
        </li> --}}
        <li class="nav-item {{request()->is('transactions') ? 'active' : ''}}">
          <a class="nav-link" href="{{route('transactions.create')}}">
            <i class="material-icons">point_of_sale</i>
            <p>Transaksi</p>
          </a>
        </li>
        @endif
      </ul>
    </div>
  </div>