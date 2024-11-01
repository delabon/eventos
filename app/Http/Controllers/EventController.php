<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventController extends Controller
{
    public function index(): Collection
    {
        return Event::all();
    }

    public function store(StoreEventRequest $request): Event
    {
        /** @var Event $event */
        $event = Event::create($request->validated());

        return $event;
    }

    public function show(Event $event): Event
    {
        return $event;
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
