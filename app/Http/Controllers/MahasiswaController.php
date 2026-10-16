<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $mahasiswa = Mahasiswa::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('mahasiswa.index', compact('mahasiswa', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'email' => 'required|email',
        ], [
            'nim.unique' => 'NIM tersebut sudah terdaftar. Silakan gunakan NIM lain.',
        ]);

        Mahasiswa::create($data);
        
        return redirect('/mahasiswa')->with('success', 'Data berhasil disimpan!');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }
    
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nama' => 'required',
            'nim' => [
                'required',
                Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->id),
            ],
            'email' => 'required|email',
        ], [
            'nim.unique' => 'NIM tersebut sudah terdaftar. Silakan gunakan NIM lain.',
        ]);

        $mahasiswa->update($data);
        
        return redirect('/mahasiswa')
            ->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect('/mahasiswa')
            ->with('success', 'Data berhasil dihapus!');
    }
}
