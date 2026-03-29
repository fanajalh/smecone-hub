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
            'gambar.*' => 'nullable|image|max:2048', // Validate each image in array
        ]);

        $data = $request->except('gambar');

        // Jika ada upload gambar/sertifikat
        if ($request->hasFile('gambar')) {
            $images = [];
            foreach ($request->file('gambar') as $file) {
                $images[] = $file->store('prestasi', 'public');
            }
            $data['gambar'] = $images;
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
            'kategori' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('gambar');

        // Jika ada upload poster event
        if ($request->hasFile('gambar')) {
            $images = [];
            foreach ($request->file('gambar') as $file) {
                $images[] = $file->store('event', 'public');
            }
            $data['gambar'] = $images;
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