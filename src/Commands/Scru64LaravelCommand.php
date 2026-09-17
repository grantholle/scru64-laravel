<?php

namespace GrantHolle\Scru64Laravel\Commands;

use Illuminate\Console\Command;

class Scru64LaravelCommand extends Command
{
    public $signature = 'scru64-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
