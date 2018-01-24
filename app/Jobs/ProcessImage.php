<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use File;
use App\Asset;
use Intervention\Image\ImageManager;
use Mockery\Exception;


class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileLoc, $width, $quality;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileLocation, $width, $quality = 60)
    {
        $this->fileLoc = $fileLocation;
        $this->width = $width;
        $this->quality = $quality;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $manager = new ImageManager(array('driver' => 'imagick'));
        try {
            $img = $manager->make(public_path(). '/storage/' . $this->fileLoc)->resize($this->width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path(). '/storage/' . $this->fileLoc, $this->quality);
        }
        catch (Exception $e) {
            report($e);

         }


    }
}
