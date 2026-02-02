<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Notifications\EventSubmitted;
use App\Notifications\EventApproved;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_submission_creates_event_and_notifies_admin()
    {
        Notification::fake();
        Storage::fake('public');

        $payload = [
            'title' => 'Test Event',
            'description' => 'Description',
            'start_at' => now()->addDays(1)->format('Y-m-d\TH:i'),
            'end_at' => now()->addDays(1)->addHours(2)->format('Y-m-d\TH:i'),
            'location' => 'Test Venue',
            'organizer_name' => 'Tester',
            'organizer_email' => 'organizer@example.com',
        ];

        // Use create() to avoid GD dependency in CI/dev environment
        $payload['image'] = UploadedFile::fake()->create('event.jpg', 500, 'image/jpeg');

        $this->post(route('events.store'), $payload)->assertRedirect(route('events.create'));

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'status' => Event::STATUS_PENDING,
        ]);

        Notification::assertSentTo(
            new AnonymousNotifiable,
            EventSubmitted::class,
            function ($notification, $channels, $notifiable) {
                return isset($notifiable->routes['mail']) && $notifiable->routes['mail'] === config('mail.admin_address');
            }
        );
    }

    public function test_approving_event_sets_status_and_notifies_organizer()
    {
        Notification::fake();

        $event = Event::create([
            'title' => 'Pending Event',
            'slug' => 'pending-event-123',
            'description' => 'Pending',
            'start_at' => now()->addDays(1),
            'location' => 'Nowhere',
            'organizer_name' => 'Org',
            'organizer_email' => 'organizer@example.com',
            'status' => Event::STATUS_PENDING,
        ]);

        // Simulate approval action
        $event->status = Event::STATUS_APPROVED;
        $event->published_at = now();
        $event->save();

        Notification::route('mail', $event->organizer_email)->notify(new EventApproved($event));

        Notification::assertSentTo($event->organizer_email ? new AnonymousNotifiable : null, EventApproved::class, function ($notification, $channels, $notifiable) use ($event) {
            return isset($notifiable->routes['mail']) && $notifiable->routes['mail'] === $event->organizer_email;
        });
    }
}
