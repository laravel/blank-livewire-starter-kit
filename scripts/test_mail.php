<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo env('MAIL_HOST') . ':' . env('MAIL_PORT') . PHP_EOL;

try {
    Illuminate\Support\Facades\Mail::raw('Teste Mailtrap via script', function ($m) {
        $m->to('ljadise@gmail.com')->subject('Teste Mailtrap script');
    });
    echo 'sent' . PHP_EOL;
} catch (Throwable $e) {
    echo 'error: ' . $e->getMessage() . PHP_EOL;
}
