<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\Base64ToImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(): Collection
    {
        return Event::with('user')->where('status', '!=', EventStatus::Draft->value)->latest()->get();
    }

    public function myEvents(Request $request): Collection
    {
        return Event::with('user')->where('user_id', $request->user()->id)->latest()->get();
    }

    public function store(StoreEventRequest $request, Base64ToImage $base64ToImage): array
    {
        $event = Event::make($request->validated());
        $event->user_id = $request->user()->id;
        $event->cover_image = $base64ToImage->handle($request->cover_image);
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

    public function update(UpdateEventRequest $request, Event $event, Base64ToImage $base64ToImage): Event
    {
        $event->update($request->validated());

        if (preg_match('/^data:image\/(\w+);base64,/', $request->cover_image)) {
            $newImagePath = $base64ToImage->handle($request->cover_image);
            Storage::disk('public')->delete($event->cover_image);
            $event->cover_image = $newImagePath;
            $event->save();
        }

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
