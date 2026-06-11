<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Resident;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('resident.block')->latest()->get();
        // Only fetch active residents for the selection dropdown
        $residents = Resident::where('status', 'active')->orderBy('name')->get();
        return view('admin.vehicles.index', compact('vehicles', 'residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'plate_number' => 'required|unique:vehicles',
            'type' => 'required',
            'brand_model_color' => 'nullable',
        ]);

        $vehicle = Vehicle::create($validated);

        if ($request->ajax()) {
            return response()->json($vehicle);
        }

        return redirect()->back()->with('success', 'Kendaraan berhasil didaftarkan');
    }

    public function show($id)
    {
        $vehicle = Vehicle::with('resident')->findOrFail($id);
        return response()->json($vehicle);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'plate_number' => 'required|unique:vehicles,plate_number,' . $id,
            'type' => 'required',
            'brand_model_color' => 'nullable',
            'status' => 'required'
        ]);

        $vehicle->update($validated);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus');
    }
}
