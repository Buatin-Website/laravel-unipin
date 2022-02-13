<?php

namespace Buatin\LaravelUnipin\Commands;

use Buatin\LaravelUnipin\Facades\Unipin;
use Illuminate\Console\Command;

class UnipinFetchGame extends Command
{
    public $signature = 'unipin:fetch-game';

    public $description = 'Fetch game from Unipin';

    public function handle(): int
    {
        $this->info('Fetching game from Unipin...');
        $fetchGame = Unipin::fetchGame();
        if ($fetchGame['status']) {
            $this->info($fetchGame['message']);
            return self::SUCCESS;
        } else {
            $this->error($fetchGame['message']);
            return self::FAILURE;
        }
    }
}
