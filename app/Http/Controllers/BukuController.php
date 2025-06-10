<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Resources\BukuResource;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index($userId)
    {
        return BukuResource::collection(Buku::where('user_id', $userId)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'user_id' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $buku = Buku::create([
            'title' => $request->title,
            'author' => $request->author,
            'user_id' => $request->user_id,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil disimpan',
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        \Log::info('Data sebelum update:', $buku->toArray());
        \Log::info('Data dikirim:', $request->only('title', 'author'));

        $buku->update($request->only('title', 'author'));

        \Log::info('Data setelah update:', $buku->fresh()->toArray());

        return new BukuResource($buku->fresh());
    }



    public function destroy(Buku $buku)
    {
        $buku->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

