<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketTypeRequest;
use App\Http\Requests\UpdateTicketTypeRequest;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Collection;

class TicketTypeController extends Controller
{
    public function index(Event $event): Collection
    {
        return $event->ticketTypes;
    }

    public function store(StoreTicketTypeRequest $request): TicketType
    {
        $ticketType = TicketType::make($request->validated());
        $ticketType->user_id = $request->user()->id;
        $ticketType->save();

        return $ticketType;
    }

    public function show(TicketType $ticketType): TicketType
    {
        return $ticketType;
    }

    public function update(UpdateTicketTypeRequest $request, TicketType $ticketType): TicketType
    {
        $ticketType->update($request->validated());

        return $ticketType;
    }

    public function destroy(TicketType $ticketType): array
    {
        $ticketType->delete();

        return [
            'success' => true
        ];
    }
}
