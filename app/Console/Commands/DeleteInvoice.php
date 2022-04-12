<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-invoice:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting unpaid invoice greater than 48 hrs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('statuses')->where('status', '=', 0)->update(['active' => 1]);
    }
}
