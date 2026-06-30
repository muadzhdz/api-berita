<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BeritaController extends Controller
{
    public function index()
    {
        return response()->json(
            Berita::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_berita'=>'required',
            'konten_berita'=>'required',
            'gambar_berita'=>'required|image|max:5120'
        ]);

        $namaFile = null;

        if($request->hasFile('gambar_berita'))
        {
            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('gambar_berita'));

            $image->scale(width:800);

            $namaFile = time().'.webp';

            Storage::disk('public')->put(
                'berita/'.$namaFile,
                (string)$image->toWebp(70)
            );
        }

        $berita = Berita::create([
            'judul_berita'=>$request->judul_berita,
            'konten_berita'=>$request->konten_berita,
            'gambar_berita'=>$namaFile
        ]);

        return response()->json($berita,201);
    }

    public function show($id)
    {
        return Berita::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul_berita'=>'required',
            'konten_berita'=>'required',
            'gambar_berita'=>'nullable|image|max:5120'
        ]);

        if($request->hasFile('gambar_berita'))
        {
            if($berita->gambar_berita){
                Storage::disk('public')
                ->delete('berita/'.$berita->gambar_berita);
            }

            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('gambar_berita'));

            $image->scale(width:800);

            $namaFile = time().'.webp';

            Storage::disk('public')->put(
                'berita/'.$namaFile,
                (string)$image->toWebp(70)
            );

            $berita->gambar_berita = $namaFile;
        }

        $berita->judul_berita = $request->judul_berita;
        $berita->konten_berita = $request->konten_berita;

        $berita->save();

        return response()->json($berita);
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if($berita->gambar_berita){
            Storage::disk('public')
            ->delete('berita/'.$berita->gambar_berita);
        }

        $berita->delete();

        return response()->json([
            'message'=>'Data berhasil dihapus'
        ]);
    }
}
