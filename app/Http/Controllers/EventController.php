<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventSubmissionRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EventSubmitted;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', Event::STATUS_APPROVED)
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('status', Event::STATUS_APPROVED)->firstOrFail();

        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.submit');
    }

    public function store(EventSubmissionRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $data['image_path'] = $path;
        }

        $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        $data['status'] = Event::STATUS_PENDING;

        $event = Event::create($data);

        // Notify admin(s)
        Notification::route('mail', config('mail.admin_address', 'admin@example.com'))
            ->notify(new EventSubmitted($event));

        return redirect()->route('events.create')->with('success', 'Evento submetido com sucesso e aguarda aprovação.');
    }
}
