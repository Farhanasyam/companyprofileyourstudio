@extends('admin.layout')

@section('title', 'Riwayat Order')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Riwayat Order</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1"><i class="bi bi-cart-check me-2"></i>Riwayat Order</h2>
        <p class="text-muted mb-0 small">Daftar pemesanan dari tombol &quot;Pesan&quot; di halaman produk website.</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pemesan</th>
                            <th>No. HP</th>
                            <th>Barang</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td><strong>{{ $order->nama_pemesan }}</strong></td>
                                <td>{{ $order->no_hp }}</td>
                                <td>
                                    @foreach($order->items ?? [] as $item)
                                        <span class="badge bg-secondary me-1">{{ $item['product_name'] ?? '-' }} × {{ $item['qty'] ?? 0 }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        @else
            <p class="text-muted mb-0">Belum ada order. Order dari tombol "Pesan" di website akan tersimpan di sini.</p>
        @endif
    </div>
</div>
@endsection
