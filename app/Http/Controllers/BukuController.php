<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->header('Authorization');

        if ($userId) {
            $data = Buku::where('email', $userId)
                ->orWhereNull('email')
                ->get()
                ->map(function ($item) use ($userId) {
                    $item->mine = $item->email === $userId ? 1 : 0;
                    return $item;
                });
        } else {
            $data = Buku::whereNull('email')
                ->get()
                ->map(function ($item) {
                    $item->mine = 0;
                    return $item;
                });
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('buku', 'public');
        $email = $request->header("Authorization");

        $buku = Buku::create([
            'email' => $email,
            'title' => $request->title,
            'author' => $request->author,
            'image' => $path,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Buku berhasil ditambahkan."
        ]);
    }

    public function show($id)
    {
        return Buku::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $buku = Buku::findOrFail($id);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($buku->image);
            $path = $request->file('image')->store('buku', 'public');
            $buku->image = $path;
        }

        if ($request->has('title')) {
            $buku->title = $request->title;
        }

        if ($request->has('author')) {
            $buku->author = $request->author;
        }

        $buku->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        Storage::disk('public')->delete($buku->image);
        $buku->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}