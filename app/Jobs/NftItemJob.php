<?php

namespace App\Jobs;

use App\Models\Nft;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class NftItemJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $nftItems;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // get session nft
        $this->nftItems = Session::get('nft_item');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! empty($this->nftItems)) {
            try {
                foreach ($this->nftItems as  $item) {
                    // get file info
                    $extension = pathinfo($item['image_url']);
                    $fileName = time().'_'.$extension['basename'];
                    // save file with url
                    $image = InterventionImage::make($item['image_url']);
                    $image->encode($extension['extension']);
                    // upload to s3
                    if (Storage::disk('s3')->put('/'.$fileName, $image->__toString(), 'public')) {
                        // get file path
                        $fullPath = Storage::disk('s3')->url($fileName);
                        // update nft with serial
                        Nft::where(['serial_no' => $item['serial_no'], 'image_url' => $item['image_url']])
                            ->update(['image_url' => $fullPath]);
                    }
                }
            } catch (\Exception $e) {
                Log::error($e);
            }
            // remove session
            Session::forget('nft_item');
        }
    }
}
