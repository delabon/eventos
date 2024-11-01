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

    public function store(StoreTicketTypeRequest $request)
    {
        //
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
