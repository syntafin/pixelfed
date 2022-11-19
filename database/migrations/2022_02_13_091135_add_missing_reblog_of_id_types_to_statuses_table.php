<?php

use Illuminate\Database\Migrations\Migration;
use App\Status;

class AddMissingReblogOfIdTypesToStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Status::whereNotNull('reblog_of_id')
            ->whereNull('type')
            ->update([
                'type' => 'share'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
