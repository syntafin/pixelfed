<?php

namespace App\Jobs\MediaPipeline;

use App\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\MediaStorageService;

class MediaStoragePipeline implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function handle()
    {
        MediaStorageService::store($this->media);
    }
}
