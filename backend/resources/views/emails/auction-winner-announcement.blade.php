<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>lelangmudah.id</title>

    {{-- Link google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    {{-- code CSS --}}
    <style>
        .heading {
            color: #383838;
            font-weight: 600;
        }

        .description {
            color: #3a3a3a;
            font-weight: 500;
        }

        main {
            font-family: 'Inter', sans-serif;
            line-height: 1.5
        }
    </style>
</head>

<body>
    <main>
        <h1 class="heading">Halo, {{ $data['detail_pemenang']['username'] }}</h1>
        <p class="description">Selamat! Kami dengan senang hati mengumumkan bahwa Anda terpilih sebagai pemenang lelang
            <strong>{{ $data['detail_lelang']['nama_lot'] }} </strong>kategori
            <strong> {{ $data['detail_lelang']['kategori_lot'] }}</strong> yang diselenggarakan secara online pada
            tanggal
            <strong>{{ $data['detail_lelang']['tgl_mulai'] }}</strong>
            s/d <strong>{{ $data['detail_lelang']['tgl_selesai'] }}</strong>.

        </p>
        <p class="description"> Anda telah berhasil menawar dengan harga
            tertinggi sebesar
            <strong>Rp {{ number_format($data['detail_lelang']['harga_akhir'], 2, '.', ',') }}</strong> selama periode
            lelang yang telah ditentukan.
        </p>
        <p class="description">Kami mengucapkan selamat atas pencapaian Anda dan berharap Anda menikmati
            pengalaman berpartisipasi dalam lelang kami. Untuk informasi lebih lanjut mengenai proses pembayaran dan
            pengiriman barang,silahkan menghubungi petugas kami
            <strong>{{ $data['detail_petugas']['nama_petugas'] }}</strong>
            melalui nomor telepon whatsapp <strong>{{ $data['detail_petugas']['telp'] }}</strong>
        </p>
        <p class="description">Salam hangat</p>
        <p class="description">lelangmudah.id</p>
    </main>
</body>

</html>
