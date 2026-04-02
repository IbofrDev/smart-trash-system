<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        .info { margin-bottom: 15px; }
        .info span { font-weight: bold; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; }
        .summary td { padding: 8px 12px; text-align: center; background: #f5f5f5; }
        .summary td strong { display: block; font-size: 16px; color: #007bff; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        table.data th { background-color: #007bff; color: white; }
        table.data tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA MAHASISWA</h2>
        <p>Sistem Tempat Sampah Pintar - Politeknik Negeri Banjarmasin</p>
    </div>

    <div class="info">
        <p>Dicetak: <span>{{ now()->format('d M Y, H:i') }}</span></p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td>
                    Total Mahasiswa
                    <strong>{{ number_format($summary['total_mahasiswa']) }}</strong>
                </td>
                <td>
                    Total Berat Sampah
                    <strong>{{ number_format($summary['total_berat'], 2) }} kg</strong>
                </td>
                <td>
                    Total Poin
                    <strong>{{ number_format($summary['total_poin']) }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="30">No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Level</th>
                <th class="text-right">Total Poin</th>
                <th class="text-right">Berat (kg)</th>
                <th class="text-right">Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $index => $mhs)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $mhs->name }}</td>
                <td>{{ $mhs->nim ?? '-' }}</td>
                <td>{{ $mhs->level->nama_level ?? '-' }}</td>
                <td class="text-right">{{ number_format($mhs->total_poin) }}</td>
                <td class="text-right">{{ number_format($mhs->transaksi_sampah_sum_berat ?? 0, 2) }}</td>
                <td class="text-right">{{ number_format($mhs->transaksi_sampah_count) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data mahasiswa</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem Tempat Sampah Pintar &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>