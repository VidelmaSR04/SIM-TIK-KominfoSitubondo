<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FontsCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fonts:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if required Inter font files are present';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fonts = [
            'Inter-Regular.ttf' => 'Regular',
            'Inter-Medium.ttf' => 'Medium',
            'Inter-SemiBold.ttf' => 'SemiBold',
            'Inter-Bold.ttf' => 'Bold',
        ];

        $allFound = true;
        $basePath = public_path('fonts/Inter/');

        foreach ($fonts as $file => $label) {
            $path = $basePath . $file;
            if (!File::exists($path)) {
                $this->error("Missing font: {$file} ({$label}) at {$path}");
                $allFound = false;
            } else {
                $this->info("Found: {$file} ({$label})");
            }
        }

        // Check fallback font
        $fallbackPath = resource_path('fonts/fallback.ttf');
        if (!File::exists($fallbackPath)) {
            $this->warning("Fallback font not found at {$fallbackPath}. Consider adding a fallback font.");
        } else {
            $this->info("Fallback font found: fallback.ttf");
        }

        if ($allFound) {
            $this->info('All Inter font files are present.');
            return 0;
        } else {
            $this->error('Some font files are missing. Please add them to public/fonts/Inter/.');
            return 1;
        }
    }
}
