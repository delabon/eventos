<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(): Collection
    {
        return Event::with('user')->latest()->get();
    }

    public function myEvents(Request $request): Collection
    {
        return Event::with('user')->where('user_id', $request->user()->id)->latest()->get();
    }

    public function store(StoreEventRequest $request): array
    {
        $event = Event::make($request->validated());
        $event->user_id = $request->user()->id;
        $event->save();

        return [
            'event' => $event,
            'user' => $event->user
        ];
    }

    public function show(Event $event): array
    {
        return [
            'event' => $event,
            'user' => $event->user
        ];
    }

    public function update(UpdateEventRequest $request, Event $event): Event
    {
        $event->update($request->validated());

        return $event;
    }

    public function destroy(Event $event): array
    {
        $event->delete();

        return [
            'success' => true
        ];
    }
}
