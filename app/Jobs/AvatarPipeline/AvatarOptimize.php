<?php

namespace App\Jobs\AvatarPipeline;

use Cache;
use App\Avatar;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Image as Intervention;

class AvatarOptimize implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $profile;
    protected $current;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Profile $profile, $current)
    {
        $this->profile = $profile;
        $this->current = $current;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $avatar = $this->profile->avatar;
        $file = storage_path("app/$avatar->media_path");

        try {
            $img = Intervention::make($file)->orientate();
            $img->fit(200, 200, function ($constraint) {
                $constraint->upsize();
            });
            $quality = config_cache('pixelfed.image_quality');
            $img->save($file, $quality);

            $avatar = Avatar::whereProfileId($this->profile->id)->firstOrFail();
            $avatar->change_count = ++$avatar->change_count;
            $avatar->last_processed_at = Carbon::now();
            $avatar->save();
            Cache::forget('avatar:' . $avatar->profile_id);
            $this->deleteOldAvatar($avatar->media_path, $this->current);
        } catch (Exception $e) {
        }
    }

    protected function deleteOldAvatar($new, $current)
    {
        if (storage_path('app/'.$new) == $current ||
             Str::endsWith($current, 'avatars/default.png') ||
             Str::endsWith($current, 'avatars/default.jpg')) {
            return;
        }
        if (is_file($current)) {
            @unlink($current);
        }
    }
}
