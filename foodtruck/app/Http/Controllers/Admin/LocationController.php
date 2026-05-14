<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Locations/Index', [
            'locations' => Location::orderByDesc('date')->get(),
            'today'     => today()->toDateString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'latitude'   => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
            'date'       => 'required|date',
            'start_time' => ['required', 'date_format:H:i', function ($attr, $val, $fail) {
                if ($val < '19:00') $fail('La apertura debe ser a partir de las 19:00.');
            }],
            'end_time'   => ['required', 'date_format:H:i', function ($attr, $val, $fail) {
                if ($val > '07:00' && $val < '19:00') $fail('El cierre debe ser antes de las 07:00 (madrugada).');
            }],
        ]);

        Location::create($request->all());

        return redirect()->route('admin.locations.index')
            ->with('success', 'Ubicación creada correctamente.');
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'latitude'   => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
            'date'       => 'required|date',
            'start_time' => ['required', 'date_format:H:i', function ($attr, $val, $fail) {
                if ($val < '19:00') $fail('La apertura debe ser a partir de las 19:00.');
            }],
            'end_time'   => ['required', 'date_format:H:i', function ($attr, $val, $fail) {
                if ($val > '07:00' && $val < '19:00') $fail('El cierre debe ser antes de las 07:00 (madrugada).');
            }],
        ]);

        $location->update($request->all());

        return redirect()->route('admin.locations.index')
            ->with('success', 'Ubicación actualizada correctamente.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Ubicación eliminada correctamente.');
    }

    public function selectToday(Location $location): RedirectResponse
    {
        // Remove today from any other location first
        Location::whereDate('date', today())
            ->where('id', '!=', $location->id)
            ->update(['date' => null]);

        $location->update(['date' => today()->toDateString()]);

        return redirect()->route('admin.locations.index')
            ->with('success', "'{$location->name}' seleccionada como ubicación de hoy.");
    }
}
