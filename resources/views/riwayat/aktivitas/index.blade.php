@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history mr-2"></i>Riwayat Aktivitas Pengguna
        </h4>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                 aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-file-export mr-2"></i>Export Data
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center" width="12%">Tanggal</th>
                        <th width="12%">Tipe Aktivitas</th>
                        <th width="15%">Subjek</th>
                        <th>Deskripsi</th>
                        <th width="12%">User</th>
                        <th width="12%">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $a)
                    <tr>
                        <td class="text-center">
                            <small class="text-muted">{{ $a->created_at->format('d M Y') }}</small><br>
                            <strong>{{ $a->created_at->format('H:i') }}</strong>
                        </td>
                        <td>
                            <span class="badge 
                                @if($a->tipe_aktivitas == 'login') badge-success
                                @elseif($a->tipe_aktivitas == 'logout') badge-secondary
                                @elseif($a->tipe_aktivitas == 'delete') badge-danger
                                @elseif($a->tipe_aktivitas == 'create') badge-info
                                @elseif($a->tipe_aktivitas == 'update') badge-warning
                                @else badge-primary
                                @endif">
                                {{ ucfirst($a->tipe_aktivitas) }}
                            </span>
                        </td>
                        <td>
                            @if($a->subjek_tipe && $a->subjek_id)
                                <span class="badge badge-light">
                                    {{ class_basename($a->subjek_tipe) }} #{{ $a->subjek_id }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="activity-description">
                                {!! nl2br(e($a->deskripsi)) !!}
                            </div>
                        </td>
                        <td>
                            @if($a->user)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm mr-2">
                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                            {{ substr($a->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    {{ $a->user->name }}
                                </div>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            <code>{{ $a->ip_address ?? '-' }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-database fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Tidak ada aktivitas ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
           {{ $aktivitas->links() }}
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        background-color: #f8f9fc;
    }
    
    .table {
        font-size: 0.9rem;
    }
    
    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .avatar-sm {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-title {
        font-size: 0.8rem;
    }
    
    .activity-description {
        max-width: 300px;
        white-space: pre-wrap;
        word-break: break-word;
    }
    
    code {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
        color: #e83e8c;
        background-color: #f8f9fa;
        border-radius: 0.2rem;
    }
</style>
@endsection