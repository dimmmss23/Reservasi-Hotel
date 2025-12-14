<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;

class KamarController extends Controller
{
    public function index()
    {
        $data = Kamar::all();
        return view('admin.kamar.index', compact('data'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $r)
    {
        $file = $r->file('foto')->store('kamar', 'public');
        
        Kamar::create([
            'nama_kamar' => $r->nama_kamar,
            'tipe' => $r->tipe,
            'harga' => $r->harga,
            'jumlah_bed' => $r->jumlah_bed,
            'deskripsi' => $r->deskripsi,
            'foto' => $file
        ]);
        
        return redirect('/admin/kamar');
    }

    public function listTamu()
    {
        $data = Kamar::all();
        return view('tamu.kamar.index', compact('data'));
    }

    public function detail($id)
    {
        $kamar = Kamar::find($id);
        $isReserved = $kamar->isReserved();
        $activeReservation = $kamar->activeReservation();
        
        return view('tamu.kamar.detail', compact('kamar', 'isReserved', 'activeReservation'));
    }

    public function edit($id)
    {
        $kamar = Kamar::find($id);
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $r, $id)
    {
        $kamar = Kamar::find($id);
        
        $data = [
            'nama_kamar' => $r->nama_kamar,
            'tipe' => $r->tipe,
            'harga' => $r->harga,
            'jumlah_bed' => $r->jumlah_bed,
            'deskripsi' => $r->deskripsi,
        ];
        
        if ($r->hasFile('foto')) {
            $file = $r->file('foto')->store('kamar', 'public');
            $data['foto'] = $file;
        }
        
        $kamar->update($data);
        
        return redirect('/admin/kamar')->with('success', 'Kamar berhasil diupdate');
    }

    public function destroy($id)
    {
        Kamar::find($id)->delete();
        return redirect('/admin/kamar')->with('success', 'Kamar berhasil dihapus');
    }

    public function pesan($id)
    {
        $kamar = Kamar::find($id);
        $isReserved = $kamar->isReserved();
        $activeReservation = $kamar->activeReservation();
        
        return view('tamu.kamar.pesan', compact('kamar', 'isReserved', 'activeReservation'));
    }
}