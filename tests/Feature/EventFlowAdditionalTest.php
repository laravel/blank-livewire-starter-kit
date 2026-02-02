<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Notifications\EventRejected;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventFlowAdditionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejecting_event_sends_notification_with_reason()
    {
        Notification::fake();

        $event = Event::create([
            'title' => 'To be rejected',
            'slug' => 'to-be-rejected-1',
            'description' => 'desc',
            'start_at' => now()->addDays(2),
            'location' => 'here',
            'organizer_name' => 'Org',
            'organizer_email' => 'organizer2@example.com',
            'status' => Event::STATUS_PENDING,
        ]);

        $reason = 'Não se encaixa nas diretrizes';

        Notification::route('mail', $event->organizer_email)
            ->notify(new EventRejected($event, $reason));

        Notification::assertSentTo(
            new AnonymousNotifiable,
            EventRejected::class,
            function ($notification, $channels, $notifiable) use ($reason) {
                return $notification->reason === $reason && isset($notifiable->routes['mail']);
            }
        );
    }

    public function test_image_is_stored_on_submission()
    {
        Storage::fake('public');

        $payload = [
            'title' => 'Image Event',
            'description' => 'Image desc',
            'start_at' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'location' => 'loc',
            'organizer_name' => 'Img Org',
            'organizer_email' => 'img@example.com',
        ];

        $payload['image'] = UploadedFile::fake()->create('picture.png', 200, 'image/png');

        $this->post(route('events.store'), $payload)->assertRedirect(route('events.create'));

        $event = Event::where('title', 'Image Event')->first();
        $this->assertNotNull($event->image_path);
        Storage::disk('public')->assertExists($event->image_path);
    }

    public function test_events_index_shows_only_approved()
    {
        Event::create([
            'title' => 'Approved Event',
            'slug' => 'approved-event',
            'description' => 'ok',
            'start_at' => now()->addDays(1),
            'location' => 'loc',
            'organizer_name' => 'A',
            'organizer_email' => 'a@example.com',
            'status' => Event::STATUS_APPROVED,
        ]);

        Event::create([
            'title' => 'Pending Event',
            'slug' => 'pending-event',
            'description' => 'pending',
            'start_at' => now()->addDays(1),
            'location' => 'loc',
            'organizer_name' => 'P',
            'organizer_email' => 'p@example.com',
            'status' => Event::STATUS_PENDING,
        ]);

        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertSee('Approved Event');
        $response->assertDontSee('Pending Event');
    }
}
