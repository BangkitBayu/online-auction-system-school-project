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


        $expired_auctions = Lelang::with(['winner.masyarakats'])
            ->where('tgl_akhir_lelang', '<=', now())
            ->where('status', '=', 'dibuka')
            ->get();

        $this->info('Pengumuman lelang ' . now());
        if ($expired_auctions) {
            $auction_ids = $expired_auctions->pluck('id_lelang');

            foreach ($expired_auctions as $auction) {
                try {
                    $winner_user = $auction->winner?->masyarakats;

                    if ($winner_user) {
                        $this->info('Pengumuman lelang kepada ' . $winner_user->username);
                        Mail::to($winner_user->email)->queue(new SendEmail($winner_user));
                    }
                } catch (Throwable $error) {
                    $this->info($error->getMessage());
                }
            }


            Lelang::whereIn('id_lelang', $auction_ids)->update(['status' => 'ditutup']);


            return Command::SUCCESS;
        }
    }
}
