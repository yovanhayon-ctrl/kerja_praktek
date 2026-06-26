@extends('admin.layouts.admin')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="container-fluid py-5 d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="card border-0 shadow-sm" style="border-radius: 16px; max-width: 450px; width: 100%;">
        <div class="card-body p-5 text-center">
            
            {{-- Ikon Gembok Merah --}}
            <div class="mb-4">
                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 100px; height: 100px;">
                    <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem;"></i>
                </div>
            </div>

            {{-- Judul Error --}}
            <h1 class="fw-bold text-dark mb-1" style="font-size: 2.5rem;">403</h1>
            <h5 class="fw-bold text-dark mb-3">Akses Ditolak!</h5>
            
            {{-- Pesan Error Dinamis (Otomatis mengambil teks dari Controller) --}}
            <div class="alert alert-danger border-0 p-3 mb-4" style="border-radius: 10px; font-size: 0.9rem;">
                {{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.' }}
            </div>

            {{-- Tombol Kembali --}}
            <a href="{{ url('/admin/dashboard') }}" class="btn btn-success w-100 py-2.5 fw-semibold" style="border-radius: 10px; background-color: #198754;">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
            
        </div>
    </div>
</div>
@endsection