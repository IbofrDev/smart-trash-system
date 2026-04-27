<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Analitik Transaksi</title>
    <style>
        /* Menggunakan font standar yang aman untuk PDF */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #334155; line-height: 1.4; margin: 0; padding: 0; }
        
        /* Layout Header menggunakan Table agar aman di DomPDF */
        .header-table { width: 100%; border-bottom: 2px solid #3b82f6; padding-bottom: 15px; margin-bottom: 20px; }
        .header-table td { vertical-align: bottom; }
        .title { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0; letter-spacing: 0.5px; }
        .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; }
        .info-text { font-size: 10px; color: #475569; line-height: 1.6; }
        .info-text strong { color: #1e293b; }

        /* Summary Cards dengan metode border-spacing */
        .summary-wrapper { width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 8px 0; margin-left: -8px; margin-right: -8px; }
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 8px; text-align: center; border-radius: 4px; width: 16.66%; }
        .summary-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block; }
        .summary-value { font-size: 16px; font-weight: bold; margin: 0; }
        
        /* Warna Khusus Data Summary */
        .val-blue { color: #2563eb; }
        .val-green { color: #059669; }
        .val-amber { color: #d97706; }
        .val-cyan { color: #0891b2; }
        .val-slate { color: #475569; }
        .val-red { color: #dc2626; }

        /* Alert Anomali */
        .alert-box { background-color: #fef2f2; border: 1px solid #fecaca; border-left: 4px solid #ef4444; padding: 10px 15px; margin-bottom: 20px; color: #991b1b; font-size: 10px; border-radius: 3px; }

        /* Main Data Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background-color: #1e293b; color: #ffffff; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        .data-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        .data-table tfoot th { background-color: #f1f5f9; color: #0f172a; border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1; font-size: 10px; }

        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-valid { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-anomali { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px dashed #cbd5e1; padding-top: 15px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60%; text-align: left;">
                <h2 class="title">Laporan Analitik Transaksi</h2>
                <div class="subtitle">Sistem Smart Waste Bank - Politeknik Negeri Banjarmasin</div>
            </td>
            <td style="width: 40%;" class="text-right info-text">
                Periode: <strong>{{ \Carbon\Carbon::parse($tanggalDari)->format('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') }}</strong><br>
                Tanggal Cetak: <strong>{{ now()->format('d M Y, H:i') }}</strong>
            </td>
        </tr>
    </table>

    <table class="summary-wrapper">
        <tr>
            <td class="summary-box">
                <span class="summary-label">Transaksi</span>
                <div class="summary-value val-blue">{{ number_format($summary['total_transaksi']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Total Berat</span>
                <div class="summary-value val-green">
                    @if($summary['total_berat'] >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 2) }} <span style="font-size:10px;">kg</span>
                    @else
                        {{ number_format($summary['total_berat'], 0) }} <span style="font-size:10px;">g</span>
                    @endif
                </div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Total Poin</span>
                <div class="summary-value val-amber">{{ number_format($summary['total_poin']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Koin Dicetak</span>
                <div class="summary-value val-cyan">{{ number_format($summary['total_koin']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Items (Botol)</span>
                <div class="summary-value val-slate">{{ number_format($summary['total_botol']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">User Aktif</span>
                <div class="summary-value val-blue">{{ number_format($summary['mahasiswa_aktif']) }}</div>
            </td>
        </tr>
    </table>

    @if($summary['total_anomali'] > 0)
    <div class="alert-box">
        <strong>PERINGATAN SISTEM:</strong> Terdapat <strong>{{ number_format($summary['total_anomali']) }}</strong> transaksi anomali (Peringatan Sensor) pada periode waktu ini. Mohon lakukan pengecekan pada log detail transaksi di bawah.
    </div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Waktu Transaksi</th>
                <th width="22%">Identitas Mahasiswa</th>
                <th width="18%">Lokasi / Mesin</th>
                <th class="text-center" width="8%">Volume</th>
                <th class="text-right" width="10%">Berat</th>
                <th class="text-right" width="8%">Poin</th>
                <th class="text-right" width="8%">Koin</th>
                <th class="text-center" width="6%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y - H:i') }}</td>
                <td>
                    <strong style="color: #0f172a;">{{ $trx->mahasiswa->name ?? 'Mahasiswa Dihapus' }}</strong>
                    @if($trx->mahasiswa->nim ?? false)
                        <br><span style="color:#64748b; font-size:8.5px; font-family: monospace;">{{ $trx->mahasiswa->nim }}</span>
                    @endif
                </td>
                <td style="color: #475569;">{{ $trx->bakSampah->nama ?? '-' }}</td>
                <td class="text-center">{{ $trx->jumlah_final ?? 0 }} pcs</td>
                <td class="text-right font-weight-bold" style="color: #059669;">
                    @if(($trx->berat ?? 0) >= 1000)
                        {{ number_format($trx->berat / 1000, 2) }} kg
                    @else
                        {{ number_format($trx->berat ?? 0, 0) }} g
                    @endif
                </td>
                <td class="text-right" style="color: #d97706;">+{{ number_format($trx->poin_didapat ?? 0) }}</td>
                <td class="text-right" style="color: #0891b2;">+{{ number_format($trx->koin_didapat ?? 0) }}</td>
                <td class="text-center">
                    @if(($trx->status_validasi ?? 'valid') === 'valid')
                        <span class="badge badge-valid">Valid</span>
                    @else
                        <span class="badge badge-anomali">Anomali</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 30px 10px; color: #94a3b8;">
                    <em>Tidak ada data transaksi yang terekam pada periode laporan ini.</em>
                </td>
            </tr>
            @endforelse
        </tbody>
        
        @if($transaksis->count() > 0)
        <tfoot>
            <tr>
                <th colspan="4" class="text-right" style="font-size: 11px;">TOTAL AKUMULASI</th>
                <th class="text-center">{{ number_format($summary['total_botol']) }}</th>
                <th class="text-right" style="color: #059669;">
                    @if($summary['total_berat'] >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 2) }} kg
                    @else
                        {{ number_format($summary['total_berat'], 0) }} g
                    @endif
                </th>
                <th class="text-right" style="color: #d97706;">{{ number_format($summary['total_poin']) }}</th>
                <th class="text-right" style="color: #0891b2;">{{ number_format($summary['total_koin']) }}</th>
                <th></th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dokumen Laporan Resmi - Digenerate otomatis oleh Sistem Smart Waste Bank &copy; {{ date('Y') }} Politeknik Negeri Banjarmasin</p>
    </div>

</body>
</html>