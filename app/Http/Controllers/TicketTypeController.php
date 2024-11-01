<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketTypeRequest;
use App\Http\Requests\UpdateTicketTypeRequest;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Collection;

class TicketTypeController extends Controller
{
    public function index(): Collection
    {
        return TicketType::all();
    }

    public function store(StoreTicketTypeRequest $request): TicketType
    {
        $ticketType = new TicketType();
        $ticketType->user_id = $request->user()->id;
        $ticketType->event_id = $request->event_id;
        $ticketType->name = $request->name;
        $ticketType->price = $request->price;
        $ticketType->quantity = $request->quantity;
        $ticketType->max_quantity_per_person = $request->max_quantity_per_person;
        $ticketType->save();

        return $ticketType;
    }

    public function show(TicketType $ticketType)
    {
        //
    }

    public function update(UpdateTicketTypeRequest $request, TicketType $ticketType)
    {
        //
    }

    public function destroy(TicketType $ticketType)
    {
        //
    }
}
