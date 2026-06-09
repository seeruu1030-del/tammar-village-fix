<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class IdCardController extends Controller
{
    public function index()
    {
        $residents = Resident::with('block')->where('status', 'active')->get();
        return view('admin.id_cards.index', compact('residents'));
    }
}
