<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 5px 0 0; font-size: 11px; color: #666; }
        .info { margin-bottom: 15px; }
        .info span { font-weight: bold; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px 10px; text-align: center; background: #f5f5f5; border: 1px solid #ddd; }
        .summary td strong { display: block; font-size: 14px; color: #28a745; }
        .summary td.koin strong { color: #17a2b8; }
        .summary td.anomali strong { color: #dc3545; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 5px 7px; text-align: left; font-size: 10px; }
        table.data th { background-color: #28a745; color: white; }
        table.data tr:nth-child(even) { background-color: #f9f9f9; }
        table.data tfoot th { background-color: #e9ecef; color: #333; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-valid { background: #d4edda; color: #155724; padding: 1px 5px; border-radius: 3px; font-size: 9px; }
        .badge-anomali { background: #fff3cd; color: #856404; padding: 1px 5px; border-radius: 3px; font-size: 9px; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI SAMPAH</h2>
        <p>Sistem Tempat Sampah Pintar - Politeknik Negeri Banjarmasin</p>
    </div>

    <div class="info">
        <p>Periode: <span>{{ \Carbon\Carbon::parse($tanggalDari)->format('d M Y') }}</span> s/d <span>{{ \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') }}</span></p>
        <p>Dicetak: <span>{{ now()->format('d M Y, H:i') }}</span></p>
    </div>

    <!-- Summary -->
    <div class="summary">
        <table>
            <tr>
                <td>
                    Total Transaksi
                    <strong>{{ number_format($summary['total_transaksi']) }}</strong>
                </td>
                <td>
                    Total Berat
                    <strong>
                        @if($summary['total_berat'] >= 1000)
                            {{ number_format($summary['total_berat'] / 1000, 2) }} kg
                        @else
                            {{ number_format($summary['total_berat'], 0) }} g
                        @endif
                    </strong>
                </td>
                <td>
                    Total Poin
                    <strong>{{ number_format($summary['total_poin']) }}</strong>
                </td>
                <td class="koin">
                    Total Koin
                    <strong>{{ number_format($summary['total_koin']) }}</strong>
                </td>
                <td>
                    Total Botol/Kaleng
                    <strong>{{ number_format($summary['total_botol']) }} pcs</strong>
                </td>
                <td>
                    Mahasiswa Aktif
                    <strong>{{ number_format($summary['mahasiswa_aktif']) }}</strong>
                </td>
                @if($summary['total_anomali'] > 0)
                <td class="anomali">
                    Anomali
                    <strong>{{ number_format($summary['total_anomali']) }}</strong>
                </td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Detail Table -->
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="25">No</th>
                <th width="80">Tanggal</th>
                <th>Mahasiswa</th>
                <th>Bak Sampah</th>
                <th class="text-center" width="45">Jumlah</th>
                <th class="text-right" width="50">Berat</th>
                <th class="text-right" width="40">Poin</th>
                <th class="text-right" width="40">Koin</th>
                <th class="text-center" width="50">Validasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                <td>
                    {{ $trx->mahasiswa->name ?? '-' }}
                    @if($trx->mahasiswa->nim ?? false)
                        <br><span style="color:#666;font-size:9px;">{{ $trx->mahasiswa->nim }}</span>
                    @endif
                </td>
                <td>{{ $trx->bakSampah->nama ?? '-' }}</td>
                <td class="text-center">{{ $trx->jumlah_final ?? 0 }}</td>
                <td class="text-right">
                    @if(($trx->berat ?? 0) >= 1000)
                        {{ number_format($trx->berat / 1000, 2) }} kg
                    @else
                        {{ number_format($trx->berat ?? 0, 0) }} g
                    @endif
                </td>
                <td class="text-right">{{ number_format($trx->poin_didapat ?? 0) }}</td>
                <td class="text-right">{{ number_format($trx->koin_didapat ?? 0) }}</td>
                <td class="text-center">
                    @if(($trx->status_validasi ?? 'valid') === 'valid')
                        <span class="badge-valid">Valid</span>
                    @else
                        <span class="badge-anomali">Anomali</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
        @if($transaksis->count() > 0)
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($summary['total_botol']) }}</th>
                <th class="text-right">
                    @if($summary['total_berat'] >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 2) }} kg
                    @else
                        {{ number_format($summary['total_berat'], 0) }} g
                    @endif
                </th>
                <th class="text-right">{{ number_format($summary['total_poin']) }}</th>
                <th class="text-right">{{ number_format($summary['total_koin']) }}</th>
                <th></th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem Tempat Sampah Pintar &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>