<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Post;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // Busca posts agendados que já passaram da hora
    $posts = Post::where('status', 'schedule')
                 ->where('published_at', '<=', Carbon::now())
                 ->get();

    foreach ($posts as $post) {
        $post->update([
            'status' => 'published',
            'published_at' => $post->published_at ?? Carbon::now() // Garante a data
        ]);
    }
})->everyMinute();
