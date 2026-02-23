@extends('admin.layout')

@section('title', 'Detail Order #' . $order->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Riwayat Order</a></li>
    <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0"><i class="bi bi-cart-check me-2"></i>Detail Order #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Nama Pemesan</strong>
                <p class="mb-0">{{ $order->nama_pemesan }}</p>
            </div>
            <div class="col-md-6">
                <strong>No. HP / WhatsApp</strong>
                <p class="mb-0"><a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $order->no_hp)) }}" target="_blank">{{ $order->no_hp }}</a></p>
            </div>
        </div>
        @if($order->catatan)
            <div class="mb-3">
                <strong>Catatan</strong>
                <p class="mb-0">{{ $order->catatan }}</p>
            </div>
        @endif
        <div class="mb-3">
            <strong>Barang dipesan</strong>
            <ul class="list-group mt-1">
                @foreach($order->items ?? [] as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item['product_name'] ?? '-' }}
                        <span class="badge bg-primary">{{ $item['qty'] ?? 0 }} pcs</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <small class="text-muted">Dibuat: {{ $order->created_at->format('d M Y H:i') }}</small>
    </div>
</div>
@endsection
