<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CloseExpiredEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:close-expired';
    protected $description = 'Close all published events that are past their scheduled date and time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $events = Event::where('status', 'published')
            ->whereRaw("STR_TO_DATE(CONCAT(date, ' ', time), '%Y-%m-%d %H:%i:%s') < ?", [$now])
            ->get();

        if ($events->isEmpty()) {
            $this->info('No expired events found.');
            return Command::SUCCESS;
        }

        foreach ($events as $event) {
            $event->update(['status' => 'closed']);
            $this->info("Closed event: {$event->title}");
        }

        return Command::SUCCESS;
    }
}
