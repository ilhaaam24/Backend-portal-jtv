<?php

namespace App\Jobs;

use App\Models\TbBerita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishBerita implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $dataupdate = TbBerita::where('status_berita','Schedule')
        ->where('date_publish_berita', '<',now())
        ->update(['status_berita' => 'Publish', 'date_perubahan_berita' => now()]);
      
        info($dataupdate);
    }
}
