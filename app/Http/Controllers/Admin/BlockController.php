<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index()
    {
        $blocks = Block::withCount(['residents' => function($query) {
            $query->where('status', 'active');
        }])->get();
        return view('admin.blocks.index', compact('blocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:blocks',
            'description' => 'nullable',
            'total_units' => 'required|integer|min:1',
            'color' => 'required|string',
        ]);

        Block::create($validated);
        return redirect()->back()->with('success', 'Blok berhasil ditambahkan');
    }

    public function show($id)
    {
        $block = Block::findOrFail($id);
        return response()->json($block);
    }

    public function update(Request $request, $id)
    {
        $block = Block::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:blocks,name,' . $id,
            'description' => 'nullable',
            'total_units' => 'required|integer|min:1',
            'color' => 'required|string',
        ]);

        $block->update($validated);
        return redirect()->back()->with('success', 'Blok berhasil diperbarui');
    }

    public function destroy($id)
    {
        $block = Block::findOrFail($id);
        $block->delete();
        return redirect()->back()->with('success', 'Blok berhasil dihapus');
    }

    public function getUnits($id)
    {
        $block = Block::findOrFail($id);
        
        // Fetch active residents with their unit info
        $residents = $block->residents()
            ->where('status', 'active')
            ->select('name', 'unit_no', 'housing_status')
            ->get()
            ->keyBy('unit_no');

        $units = [];
        $takenUnits = [];
        for ($i = 1; $i <= $block->total_units; $i++) {
            $unitNo = (string)$i;
            $resident = $residents->get($unitNo);
            
            if ($resident) {
                $units[] = [
                    'no' => $unitNo,
                    'status' => 'taken',
                    'resident_name' => $resident->name,
                    'housing_status' => $resident->housing_status, // Owner or Tenant
                ];
                $takenUnits[] = $unitNo;
            } else {
                $units[] = [
                    'no' => $unitNo,
                    'status' => 'available',
                ];
            }
        }
        
        $availableUnits = array_values(array_diff(range(1, $block->total_units), $takenUnits));

        return response()->json([
            'total' => $block->total_units,
            'units' => $units,
            'taken_count' => count($takenUnits),
            'available_count' => count($availableUnits)
        ]);
    }
}
