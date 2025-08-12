<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('test:command', function () {
    $this->info('Test command executed1211!');
});

Artisan::command('sync:items', function () {
    $this->info('Команда sync:items вызвана');

    $command = new \App\Console\Commands\SyncItemsToGoogleSheet();
    $command->setOutput($this->output);
    $command->handle();

    $this->info('Команда sync:items завершена');
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


