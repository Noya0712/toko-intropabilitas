<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        return Buku::with('kategori')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'harga' => 'required|numeric|min:1000',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku = Buku::create($request->all());
        return response()->json($buku, 201);
    }

    public function show($id)
    {
        return Buku::with('kategori')->find($id);
    }

    public function search(Request $request) {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);
    
        $query = $request->input('query');
    
        $books = Buku::where('judul', 'LIKE', '%' . $query . '%')
            ->orWhereHas('kategori', function($queryBuilder) use ($query) {
                $queryBuilder->where('nama_kategori', 'LIKE', '%' . $query . '%');
            })
            ->get();
    
        dd($books);
     
        return response()->json($books);
    }
    

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->update($request->all());
        return response()->json($buku, 200);
    }

    public function destroy($id)
    {
        Buku::destroy($id);
        return response()->json(null, 204);
    }
}
