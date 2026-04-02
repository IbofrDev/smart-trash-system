@extends('layouts.admin')

@section('title', 'Setting Poin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Konfigurasi setting poin gamifikasi</p>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Setting</th>
                        <th>Value</th>
                        <th>Deskripsi</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $index => $setting)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $setting->nama_setting }}</td>
                        <td><span class="badge bg-primary fs-6">{{ number_format($setting->value) }}</span></td>
                        <td>{{ $setting->deskripsi ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.setting-poin.edit', $setting) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Tidak ada data setting poin
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection