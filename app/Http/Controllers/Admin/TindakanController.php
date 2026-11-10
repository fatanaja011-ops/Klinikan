<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tindakan;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    public function index()
    {
        $tindakans = Tindakan::latest()->paginate(10);
        return view('admin.tindakan.index', compact('tindakans'));
    }

    public function create()
    {
        return view('admin.tindakan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_tindakan' => 'required|unique:tindakans',
            'nama_tindakan' => 'required',
            'tarif'         => 'required|numeric',
        ]);

        Tindakan::create($request->all());

        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil ditambahkan!');
    }

    public function edit(Tindakan $tindakan)
    {
        return view('admin.tindakan.edit', compact('tindakan'));
    }

    public function update(Request $request, Tindakan $tindakan)
    {
        $request->validate([
            'kode_tindakan' => 'required|unique:tindakans,kode_tindakan,' . $tindakan->id,
            'nama_tindakan' => 'required',
            'tarif'         => 'required|numeric',
        ]);

        $tindakan->update($request->all());

        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil diperbarui!');
    }

    public function destroy(Tindakan $tindakan)
    {
        $tindakan->delete();
        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil dihapus!');
    }
}