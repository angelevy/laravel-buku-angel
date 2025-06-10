<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $email = $request->header('Authorization');

        if ($email) {
            $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $path = $request->file('image')->store('gambar-buku-api', 'public');

            Buku::create([
                'title' => $request->title,
                'author' => $request->author,
                'image' => $path,
                'email' => $email,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan.'
            ]);
        }

        return response()->json([
            'message' => 'Anda Belum Login.'
        ], 401);
    }

    public function update(Request $request, $id)
    {
        $email = $request->header('Authorization');

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $bukuApi = Buku::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$bukuApi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        $bukuApi->title = $request->title;
        $bukuApi->author = $request->author;

        if ($request->hasFile('image')) {
            if ($bukuApi->image && Storage::disk('public')->exists($bukuApi->image)) {
                Storage::disk('public')->delete($bukuApi->image);
            }

            $path = $request->file('image')->store('gambar-buku-api', 'public');
            $bukuApi->image = $path;
        }

        $bukuApi->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    public function show($id)
    {
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        return response()->json($buku);
    }

    public function destroy(Request $request, $id)
    {
        $email = $request->header('Authorization');

        $bukuApi = Buku::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$bukuApi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        if ($bukuApi->image && Storage::disk('public')->exists($bukuApi->image)) {
            Storage::disk('public')->delete($bukuApi->image);
        }

        $bukuApi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
