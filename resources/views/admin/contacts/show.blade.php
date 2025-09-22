@extends('admin.layout')

@section('title', 'Detail Kontak')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Kontak</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama:</th>
                                    <td>{{ $contact->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $contact->email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon:</th>
                                    <td>{{ $contact->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Subjek:</th>
                                    <td>{{ $contact->subject }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $contact->status_badge }}">
                                            {{ $contact->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal:</th>
                                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($contact->replied_at)
                                <tr>
                                    <th>Dibalas:</th>
                                    <td>{{ $contact->replied_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Pesan:</h5>
                            <div class="border p-3 rounded">
                                {{ $contact->message }}
                            </div>
                            
                            @if($contact->admin_reply)
                            <h5 class="mt-4">Balasan Admin:</h5>
                            <div class="border p-3 rounded bg-light">
                                {{ $contact->admin_reply }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
