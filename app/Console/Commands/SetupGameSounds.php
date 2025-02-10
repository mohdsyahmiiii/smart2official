<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupGameSounds extends Command
{
    protected $signature = 'game:setup-sounds';
    protected $description = 'Setup sound files for the word game';

    public function handle()
    {
        // Create sounds directory if it doesn't exist
        if (!File::exists(public_path('sounds'))) {
            File::makeDirectory(public_path('sounds'), 0755, true);
        }

        // Download sound files
        $sounds = [
            'correct' => 'https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3',
            'wrong' => 'https://assets.mixkit.co/active_storage/sfx/2014/2014-preview.mp3',
            'switch' => 'https://assets.mixkit.co/active_storage/sfx/2015/2015-preview.mp3'
        ];

        foreach ($sounds as $name => $url) {
            $path = public_path("sounds/{$name}.mp3");
            if (!File::exists($path)) {
                file_put_contents($path, file_get_contents($url));
                $this->info("Downloaded {$name}.mp3");
            }
        }

        $this->info('Sound files setup completed!');
    }
} 