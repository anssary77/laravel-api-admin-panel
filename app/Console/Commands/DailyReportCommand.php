<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Mail\DailyReportMail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class DailyReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily report of new posts and users and email to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = now()->subDay();
        $date = $yesterday->format('Y-m-d');

        $newPostsCount = Post::whereDate('created_at', $date)->count();
        $newUsersCount = User::whereDate('created_at', $date)->count();

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new DailyReportMail($newPostsCount, $newUsersCount, $date));
        }

        $this->info("Daily report for {$date} sent to admins.");
    }
}
