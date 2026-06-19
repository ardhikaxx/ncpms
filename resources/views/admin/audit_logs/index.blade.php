@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Log Audit RME</h1>
        <p class="page-subtitle">Rekam jejak akses dan modifikasi data rekam medis elektronik (Sesuai Permenkes 24/2022).</p>
    </div>
</div>

<div class="ncpms-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu (WIB)</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Modul Target</th>
                    <th>Deskripsi Aktivitas</th>
                    <th>Alamat IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->user ? $log->user->nama_lengkap : 'Sistem' }}</td>
                    <td>
                        @if($log->action == 'create') <span class="badge-pill badge-soft-success">CREATE</span>
                        @elseif($log->action == 'update') <span class="badge-pill badge-soft-warning">UPDATE</span>
                        @elseif($log->action == 'delete') <span class="badge-pill badge-soft-danger">DELETE</span>
                        @elseif($log->action == 'print') <span class="badge-pill badge-soft-primary">PRINT</span>
                        @else <span class="badge-pill badge-soft-gray">{{ strtoupper($log->action) }}</span>
                        @endif
                    </td>
                    <td>{{ str_replace('App\\Models\\', '', $log->model_type) }} #{{ $log->model_id }}</td>
                    <td>{{ $log->deskripsi }}</td>
                    <td class="text-muted small">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Belum ada log audit yang terekam.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
