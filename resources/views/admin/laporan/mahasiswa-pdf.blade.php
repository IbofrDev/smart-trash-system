<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
        }

        .info span {
            font-weight: bold;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 8px 10px;
            text-align: center;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }

        .summary td strong {
            display: block;
            font-size: 14px;
            color: #007bff;
        }

        .summary td.koin strong {
            color: #17a2b8;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th,
        table.data td {
            border: 1px solid #ddd;
            padding: 5px 7px;
            text-align: left;
            font-size: 10px;
        }

        table.data th {
            background-color: #007bff;
            color: white;
        }

        table.data tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table.data tfoot th {
            background-color: #e9ecef;
            color: #333;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .rank-1 {
            background-color: #fff8e1;
            font-weight: bold;
        }

        .rank-2 {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .rank-3 {
            background-color: #fbe9e7;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
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

    <!-- Summary -->
    <div class="summary">
        <table>
            <tr>
                <td>
                    Total Mahasiswa
                    <strong>{{ number_format($summary['total_mahasiswa']) }}</strong>
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
                    Total Transaksi
                    <strong>{{ number_format($summary['total_transaksi']) }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="25">No</th>
                <th>Nama</th>
                <th width="60">NIM</th>
                <th width="70">Level</th>
                <th class="text-right" width="55">Total Poin</th>
                <th class="text-right" width="45">Total Koin</th>
                <th class="text-right" width="55">Botol/Kaleng</th>
                <th class="text-right" width="55">Total Berat</th>
                <th class="text-right" width="45">Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $index => $mhs)
                <tr class="{{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">
                    <td class="text-center">
                        @if($index == 0) #1
                        @elseif($index == 1) #2
                        @elseif($index == 2) #3
                        @else {{ $index + 1 }}
                        @endif
                    </td>
                    <td>{{ $mhs->name }}</td>
                    <td>{{ $mhs->nim ?? '-' }}</td>
                    <td>{{ $mhs->level->nama_level ?? '-' }}</td>
                    <td class="text-right">{{ number_format($mhs->total_poin) }}</td>
                    <td class="text-right">{{ number_format($mhs->total_koin_botol ?? 0) }}</td>
                    <td class="text-right">{{ number_format($mhs->transaksi_sampah_sum_jumlah_final ?? 0) }} pcs</td>
                    <td class="text-right">
                        @php $berat = $mhs->transaksi_sampah_sum_berat ?? 0; @endphp
                        @if($berat >= 1000)
                            {{ number_format($berat / 1000, 2) }} kg
                        @else
                            {{ number_format($berat, 0) }} g
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($mhs->transaksi_sampah_count) }}x</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data mahasiswa</td>
                </tr>
            @endforelse
        </tbody>
        @if($mahasiswas->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">TOTAL</th>
                    <th class="text-right">{{ number_format($summary['total_poin']) }}</th>
                    <th class="text-right">{{ number_format($summary['total_koin']) }}</th>
                    <th class="text-right">{{ number_format($summary['total_botol']) }} pcs</th>
                    <th class="text-right">
                        @if($summary['total_berat'] >= 1000)
                            {{ number_format($summary['total_berat'] / 1000, 2) }} kg
                        @else
                            {{ number_format($summary['total_berat'], 0) }} g
                        @endif
                    </th>
                    <th class="text-right">{{ number_format($summary['total_transaksi']) }}x</th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem Tempat Sampah Pintar &copy; {{ date('Y') }}</p>
    </div>
</body>

</html>