<?php

namespace Buatin\LaravelUnipin\Commands;

use Illuminate\Console\Command;

class LaravelUnipinCommand extends Command
{
    public $signature = 'laravel-unipin';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
