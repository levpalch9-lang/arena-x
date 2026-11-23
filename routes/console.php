<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('arena:about', function () {
    $this->comment('ARENA X — esports tournament platform');
})->purpose('Show ARENA X project information');
