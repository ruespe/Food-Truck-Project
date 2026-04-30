<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rows = DB::select("SELECT DATE(created_at) as d, SUM(total_price) as t, COUNT(*) as c FROM orders WHERE status = 'confirmed' GROUP BY DATE(created_at) ORDER BY d");
        $this->line('Confirmed orders by date:');
        foreach ($rows as $row) {
            $this->line($row->d . '  total=' . $row->t . '  count=' . $row->c);
        }
        $cutoff = now()->subDays(13)->startOfDay()->toDateTimeString();
        $this->line('Cutoff (last 14 days): ' . $cutoff);
        $this->line('Today: ' . now()->toDateTimeString());
    }
}
