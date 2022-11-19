<?php

namespace App\Jobs\InboxPipeline;

use App\Util\ActivityPub\Inbox;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SharedInboxWorker implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $request;
    protected $profile;
    protected $payload;

    public $timeout = 60;
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $payload)
    {
        $this->request = $request;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new Inbox($this->request, null, $this->payload))->handleSharedInbox();
    }
}
