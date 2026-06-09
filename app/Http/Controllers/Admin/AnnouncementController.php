<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'icon' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
            $validated['image_path'] = $path;
        }

        Announcement::create($validated);
        return redirect()->back()->with('success', 'Pengumuman berhasil diterbitkan');
    }

    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        return response()->json($announcement);
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'icon' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $path = $request->file('image')->store('announcements', 'public');
            $validated['image_path'] = $path;
        }

        $announcement->update($validated);
        return redirect()->back()->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }
        $announcement->delete();
        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus');
    }
}
