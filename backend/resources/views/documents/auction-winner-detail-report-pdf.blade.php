<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif
        }

        .table-report-header,
        .table-report-main,
        .table-report-footer {
            width: 100%;
            border-collapse: collapse
        }

        .table-report-footer {
            position: fixed;
            bottom: 0;
        }

        .table-report__divider {
            border-bottom: 1px solid #2b2b2b;
        }

        .label {
            font-weight: 600;
        }

        .value {
            font-weight: 500;
            color: #404040;
        }

        th {
            background-color: #cecaca;
            padding: 0.8rem;
        }
    </style>
    <title>Bukti Pemenang Lelang</title>
</head>

<body>
    <header>
        <table class="table-report-header">
            <tr>
                <td>
                    <strong>lelangmudah.id</strong>
                    <br>
                    <small>Platfrom lelang online resmi Indonesia</small>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <h1>Bukti Pemenang Lelang</h1>
                </td>
            </tr>
            <tr>
                <td class="table-report__divider"></td>
            </tr>
        </table>
    </header>
    <main>
        <table class="table-report-main">
            <div style="padding-top: 0.6rem ; padding-bottom: 0.6rem;">A. Informasi Lelang</div>
            <tr>
                <td class="label">ID Lot Lelang: </td>
                <td>:</td>
                <td class="value">{{ $auction->id_lelang }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai: </td>
                <td>:</td>
                <td class="value">{{ $auction->tgl_mulai }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Selesai: </td>
                <td>:</td>
                <td class="value">{{ $auction->tgl_selesai }}</td>
            </tr>
            <div style="padding-top: 0.6rem ; padding-bottom: 0.6rem;">B. Spesifikasi Lot Lelang</div>
            <tr>
                <td class="label">Nama: </td>
                <td>:</td>
                <td class="value">{{ $auction->nama_lot }}</td>
            </tr>

            <tr>
                <td class="label">Kategori Lot Lelang: </td>
                <td>:</td>
                <td class="value">{{ $auction->nama_kategori_lot }}</td>
            </tr>

            <tr>
                <td class="label">Deskripsi: </td>
                <td>:</td>
                <td class="value">{{ $auction->deskripsi_lot }}</td>
            </tr>

            <tr>
                <td class="label">Harga Awal: </td>
                <td>:</td>
                <td class="value">Rp {{ number_format($auction->harga_awal, 2, '.', ',') }}</td>
            </tr>

            <div style="padding-top: 0.6rem ; padding-bottom: 0.6rem;">C. Informasi Pemenang</div>

            <tr>
                <td class="label">Nama Lengkap: </td>
                <td>:</td>
                <td class="value">{{ $history->nama_lengkap ?? 'Tidak ada penawar' }}</td>
            </tr>
            <tr>
                <td class="label">Username: </td>
                <td>:</td>
                <td class="value">{{ $history->username ?? 'Tidak ada penawar' }}</td>
            </tr>
            <tr>
                <td class="label">E-Mail: </td>
                <td>:</td>
                <td class="value">{{ $history->email ?? 'Tidak ada penawar' }}</td>
            </tr>
            <tr>
                <td class="label">No Telp: </td>
                <td>:</td>
                <td class="value">{{ $history->telp ?? 'Tidak ada penawar' }}</td>
            </tr>
        </table>
        <div style="padding-top: 0.6rem ; padding-bottom: 0.6rem;">D. Rincian Pembayaran</div>
        <table class="table-report-main">
            <thead>
                <tr>
                    <th style="text-align: left">Keterangan</th>
                    <th style="text-align: rightx">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 0.8rem">Harga Penawaran</td>
                    @if (isset($history->penawaran_harga) && $history->penawaran_harga !== null)
                        <td style="padding: 0.8rem">Rp
                            {{ number_format((float) $history->penawaran_harga, 2, '.', ',') }}
                        </td>
                    @else
                        <td style="padding: 0.8rem">Rp 0</td>
                    @endif
                </tr>
                <tr>
                    <td style="padding: 0.8rem">Total yang Perlu Dibayar</td>
                    @if (isset($history->penawaran_harga) && $history->penawaran_harga !== null)
                        <td style="padding: 0.8rem">Rp
                            {{ number_format((float) $history->penawaran_harga, 2, '.', ',') }}
                        </td>
                    @else
                        <td style="padding: 0.8rem">Rp 0</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <table class="table-report-footer">
            <tr>
                <td style="text-align: center">Dokumen ini resmi dicetak dan diterbitkan oleh lelangmudah.id</td>
            </tr>
        </table>
    </footer>
</body>

</html>
