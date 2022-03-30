<?php

namespace Buatin\LaravelUnipin\Commands;

use Buatin\LaravelUnipin\Facades\Unipin;
use Illuminate\Console\Command;

class UnipinFetchVoucher extends Command
{
    public $signature = 'unipin:fetch-voucher';

    public $description = 'Fetch voucher from Unipin';

    public function handle(): int
    {
        $this->info('Fetching voucher from Unipin...');
        $fetchVoucher = Unipin::fetchVoucher();
        if ($fetchVoucher['status']) {
            $this->info($fetchVoucher['message']);

            return self::SUCCESS;
        } else {
            $this->error($fetchVoucher['message']);

            return self::FAILURE;
        }
    }
}
