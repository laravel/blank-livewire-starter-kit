<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Notification;
use App\Models\Event;
use App\Notifications\EventSubmitted;

$event = Event::create([
    'title' => 'Queued Test Event',
    'slug' => 'queued-test-event-'.uniqid(),
    'description' => 'Sent via queued notification test',
    'start_at' => now()->addDay(),
    'location' => 'Test location',
    'organizer_name' => 'Queue Tester',
    'organizer_email' => 'organizer+queue@example.com',
    'status' => Event::STATUS_PENDING,
]);

Notification::route('mail', config('mail.admin_address'))
    ->notify(new EventSubmitted($event));

echo "Dispatched EventSubmitted for event id {$event->id}\n";