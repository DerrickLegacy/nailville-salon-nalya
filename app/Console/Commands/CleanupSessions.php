<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup {--hours=24 : Hours to keep sessions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old sessions from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $timestamp = now()->subHours($hours)->timestamp;

        $deleted = DB::table('sessions')
            ->where('last_activity', '<', $timestamp)
            ->delete();

        $this->info("Deleted {$deleted} expired sessions older than {$hours} hours.");

        return Command::SUCCESS;
    }
}
