<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Prestasi;

class AdminController extends Controller
{
    public function index()
    {
        $prestasis = Prestasi::latest()->get();
        $events = Event::latest()->get();
        
        $totalPrestasi = $prestasis->count();
        $totalEvent = $events->count();

        return view('admin.dashboard', compact('prestasis', 'events', 'totalPrestasi', 'totalEvent'));
    }

    // ==========================================
    // 🏆 MANAJEMEN PRESTASI
    // ==========================================
    public function createPrestasi()
    {
        return view('admin.prestasi.create');
    }

    public function storePrestasi(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nama_pemenang' => 'required|string|max:255',
            'kategori_juara' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $data = $request->all();

        // Jika ada upload gambar/sertifikat
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('prestasi', 'public');
        }

        Prestasi::create($data);

        return redirect('/admin/dashboard')->with('success', 'Prestasi berhasil ditambahkan!');
    }

    public function destroyPrestasi($id)
    {
        Prestasi::destroy($id);
        return back()->with('success', 'Prestasi berhasil dihapus.');
    }

    // ==========================================
    // 🎉 MANAJEMEN EVENT
    // ==========================================
    public function createEvent()
    {
        return view('admin.event.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_event' => 'required|date',
        ]);

        $data = $request->all();

        // Jika ada upload poster event
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('event', 'public');
        }

        Event::create($data);

        return redirect('/admin/dashboard')->with('success', 'Event berhasil ditambahkan!');
    }

    public function destroyEvent($id)
    {
        Event::destroy($id);
        return back()->with('success', 'Event berhasil dihapus.');
    }
}