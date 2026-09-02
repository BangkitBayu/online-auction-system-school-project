<?php

namespace App\Console\Commands;

use App\Mail\SendEmail;
use App\Models\Lelang;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CheckAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-auction-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek dan menutup sesi lelang yang sudah melewati batas waktu';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $expired_auctions = Lelang::with(['winner', 'winner.masyarakats'])
            ->where('tgl_akhir_lelang', '<=', now())
            ->where('status', '=', 'dibuka')
            ->get();

        $this->info('Pengumuman lelang ' . now());
        if ($expired_auctions) {
            // Ambil Id lelang yang sudah melampaui batas waktu
            $auction_ids = $expired_auctions->pluck('id_lelang');

            foreach ($expired_auctions as $auction) {
                $winning_bid = $auction->winner;
                $winning_user = $auction->winner?->masyarakats;

                $data = [
                    'detail_lelang' => [
                        'id' => $auction->id_lelang,
                        'nama_lot' => $auction->barang?->nama_barang,
                        'kategori_lot' => $auction->barang?->kategori_barang?->nama_kategori_barang,
                        'harga_akhir' => $auction->harga_akhir,
                        'tgl_mulai' => $auction->tgl_mulai_lelang,
                        'tgl_selesai' => $auction->tgl_akhir_lelang,
                    ],
                    'detail_pemenang' => [
                        'id' => $winning_user?->id_user,
                        'username' => $winning_user?->username,
                        'email' => $winning_user?->email,
                    ],
                    'detail_petugas' => [
                        'id' => $auction->petugas?->id_petugas,
                        'username' => $auction->petugas?->username,
                        'nama_petugas' => $auction->petugas?->nama_petugas,
                        'telp' => $auction->petugas?->telp,
                    ]
                ];

                try {
                    if ($winning_user) {
                        $this->info('Pengumuman lelang kepada ' . $winning_user->username);

                        Mail::to($winning_user->email)->send(new SendEmail($data));
                    }

                    $auction->update([
                        'status' => 'ditutup',
                        'harga_akhir' => $winning_bid?->penawaran_harga,
                        'id_user' => $winning_bid?->id_user,
                    ]);
                } catch (Throwable $error) {
                    $this->info($error->getMessage());
                }
            }



            return Command::SUCCESS;
        }
    }
}
