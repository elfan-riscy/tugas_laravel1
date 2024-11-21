<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Mengambil chirp terbaru beserta informasi pengguna yang mengirimnya
        $chirps = Chirp::with('user')->latest()->get();
        
        // Mengirimkan data chirps ke view
        return view('chirps.index', compact('chirps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }
    public function store(Request $request): RedirectResponse
{
    // Validasi input
    $validated = $request->validate([
        'message' => 'required|string|max:255',
    ]);

    // Menyimpan chirp baru yang terkait dengan user yang sedang login
    $request->user()->chirps()->create($validated);

    // Redirect ke halaman index setelah berhasil menyimpan
    return redirect(route('chirps.index'));
}

    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }
}
