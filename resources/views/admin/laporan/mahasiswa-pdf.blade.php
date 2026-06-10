<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Klasemen Mahasiswa</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #334155; line-height: 1.4; margin: 0; padding: 0; }
        
        .header-table { width: 100%; border-bottom: 2px solid #10b981; padding-bottom: 15px; margin-bottom: 20px; }
        .header-table td { vertical-align: bottom; }
        .title { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0; letter-spacing: 0.5px; }
        .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; }
        .info-text { font-size: 10px; color: #475569; line-height: 1.6; }
        .info-text strong { color: #0f172a; }

        .summary-wrapper { width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 8px 0; margin-left: -8px; margin-right: -8px; }
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 8px; text-align: center; border-radius: 4px; width: 16.66%; }
        .summary-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; display: block; }
        .summary-value { font-size: 16px; font-weight: bold; margin: 0; }
        
        .val-primary { color: #0f172a; }
        .val-success { color: #059669; }

        /* Main Data Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background-color: #f8fafc; color: #475569; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; }
        .data-table tbody tr:nth-child(even) { background-color: #fafafa; }
        .data-table tfoot th { background-color: #f8fafc; color: #0f172a; border-top: 2px solid #cbd5e1; border-bottom: 2px solid #cbd5e1; font-size: 10px; }

        /* Peringkat Juara (Top 3) */
        .rank-1 { background-color: #fffbeb !important; }
        .rank-2 { background-color: #f8fafc !important; }
        .rank-3 { background-color: #fff7ed !important; }
        
        .medal-1 { color: #d97706; font-weight: bold; font-size: 11px; background-color: #fef3c7; padding: 2px 6px; border-radius: 3px; border: 1px solid #fde68a;}
        .medal-2 { color: #475569; font-weight: bold; font-size: 11px; background-color: #f1f5f9; padding: 2px 6px; border-radius: 3px; border: 1px solid #e2e8f0;}
        .medal-3 { color: #c2410c; font-weight: bold; font-size: 11px; background-color: #ffedd5; padding: 2px 6px; border-radius: 3px; border: 1px solid #fed7aa;}

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px dashed #cbd5e1; padding-top: 15px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 65%; text-align: left;">
                <h2 class="title">Laporan Peringkat Mahasiswa</h2>
                <div class="subtitle">Leaderboard & Aktivitas Pengelolaan Sampah - Politeknik Negeri Banjarmasin</div>
            </td>
            <td style="width: 35%;" class="text-right info-text">
                Tanggal Cetak: <strong>{{ now()->format('d M Y, H:i') }}</strong><br>
                Total Terdaftar: <strong>{{ number_format($summary['total_mahasiswa']) }} Mahasiswa</strong>
            </td>
        </tr>
    </table>

    <table class="summary-wrapper">
        <tr>
            <td class="summary-box">
                <span class="summary-label">Total Mahasiswa</span>
                <div class="summary-value val-primary">{{ number_format($summary['total_mahasiswa']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Volume Berat</span>
                <div class="summary-value val-success">
                    @if($summary['total_berat'] >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 2) }} <span style="font-size:10px;">kg</span>
                    @else
                        {{ number_format($summary['total_berat'], 0) }} <span style="font-size:10px;">g</span>
                    @endif
                </div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Total Poin</span>
                <div class="summary-value val-primary">{{ number_format($summary['total_poin']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Saldo Koin</span>
                <div class="summary-value val-primary">{{ number_format($summary['total_koin']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Items (Botol)</span>
                <div class="summary-value val-primary">{{ number_format($summary['total_botol']) }}</div>
            </td>
            <td class="summary-box">
                <span class="summary-label">Total Transaksi</span>
                <div class="summary-value val-primary">{{ number_format($summary['total_transaksi']) }}</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="6%">Rank</th>
                <th width="24%">Identitas Pahlawan Lingkungan</th>
                <th width="15%">Peringkat (Level)</th>
                <th class="text-right" width="12%">Poin</th>
                <th class="text-right" width="12%">Koin</th>
                <th class="text-right" width="10%">Setoran</th>
                <th class="text-right" width="12%">Total Berat</th>
                <th class="text-center" width="9%">Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $index => $mhs)
            <tr class="{{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">
                
                <td class="text-center">
                    @if($index == 0)
                        <span class="medal-1">#1</span>
                    @elseif($index == 1)
                        <span class="medal-2">#2</span>
                    @elseif($index == 2)
                        <span class="medal-3">#3</span>
                    @else
                        <span style="color: #64748b; font-weight: bold;">{{ $index + 1 }}</span>
                    @endif
                </td>
                
                <td>
                    <strong style="color: #0f172a;">{{ Str::limit($mhs->name, 25) }}</strong>
                    <br><span style="color:#64748b; font-size:8.5px; font-family: monospace;">NIM: {{ $mhs->nim ?? '-' }}</span>
                </td>
                
                <td style="color: #0f172a; font-weight: bold;">{{ $mhs->level->nama_level ?? '-' }}</td>
                
                <td class="text-right" style="color: #059669; font-weight: bold;">{{ number_format($mhs->total_poin) }}</td>
                <td class="text-right" style="color: #0f172a; font-weight: bold;">{{ number_format($mhs->total_koin_botol ?? 0) }}</td>
                <td class="text-right" style="color: #475569;">{{ number_format($mhs->transaksi_sampah_sum_jumlah_final ?? 0) }} pcs</td>
                
                <td class="text-right" style="color: #0f172a; font-weight: bold;">
                    @php $berat = $mhs->transaksi_sampah_sum_berat ?? 0; @endphp
                    @if($berat >= 1000)
                        {{ number_format($berat / 1000, 2) }} kg
                    @else
                        {{ number_format($berat, 0) }} g
                    @endif
                </td>
                
                <td class="text-center" style="color: #0f172a;">{{ number_format($mhs->transaksi_sampah_count) }}x</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 30px 10px; color: #94a3b8;">
                    <em>Tidak ada data klasemen mahasiswa yang dapat ditampilkan.</em>
                </td>
            </tr>
            @endforelse
        </tbody>
        
        @if($mahasiswas->count() > 0)
        <tfoot>
            <tr>
                <th colspan="3" class="text-right" style="font-size: 11px;">TOTAL AKUMULASI SISTEM</th>
                <th class="text-right" style="color: #059669;">{{ number_format($summary['total_poin']) }}</th>
                <th class="text-right" style="color: #0f172a;">{{ number_format($summary['total_koin']) }}</th>
                <th class="text-right" style="color: #475569;">{{ number_format($summary['total_botol']) }} pcs</th>
                <th class="text-right" style="color: #0f172a;">
                    @if($summary['total_berat'] >= 1000)
                        {{ number_format($summary['total_berat'] / 1000, 2) }} kg
                    @else
                        {{ number_format($summary['total_berat'], 0) }} g
                    @endif
                </th>
                <th class="text-center" style="color: #0f172a;">{{ number_format($summary['total_transaksi']) }}x</th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dokumen Laporan Resmi - Digenerate otomatis oleh Sistem Smart Waste Bank &copy; {{ date('Y') }} Politeknik Negeri Banjarmasin</p>
    </div>

</body>
</html>