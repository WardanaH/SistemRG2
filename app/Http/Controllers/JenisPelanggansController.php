<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MJenisPelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class JenisPelanggansController extends Controller
{
    /**
     * Tampilkan halaman daftar jenis pelanggan.
     */
    public function index(Request $request)
    {
        // Jika request dari DataTables (AJAX)
        if ($request->ajax()) {
            $tables = MJenisPelanggan::latest()->get();

            return DataTables::of($tables)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group">
                            <button type="button" class="modal_edit btn btn-info btn-sm"
                                data-toggle="modal"
                                data-id="' . encrypt($row->id) . '"
                                data-jenis="' . e($row->jenis_pelanggan) . '"
                                data-target="#modal_edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="modal_hapus btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-id="' . encrypt($row->id) . '"
                                data-jenis="' . e($row->jenis_pelanggan) . '"
                                data-target="#modal_hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Jika bukan AJAX, tampilkan view biasa
        $jenispelanggans = MJenisPelanggan::latest()->get();
        return view('admin.jenispelanggan.index', compact('jenispelanggans'));
    }

    /**
     * Tambah data jenis pelanggan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tambah_jenispelanggan' => 'required|string|max:255',
        ]);

        $data = MJenisPelanggan::create([
            'jenis_pelanggan' => $validated['tambah_jenispelanggan'],
        ]);

        // Logging (opsional)
        // if (method_exists($this, 'createlog') && Auth::check()) {
        //     $isi = Auth::user()->username . " menambahkan jenis pelanggan {$data->jenis_pelanggan}.";
        //     $this->createlog($isi, "add");
        // }

        // Cek tipe request
        if ($request->ajax()) {
            return Response::json(['success' => true, 'message' => 'Jenis pelanggan berhasil ditambahkan.']);
        }

        $isi = auth()->user()->username . " telah menambahkan jenis pelanggan " . $data->jenis_pelanggan . ".";
        $this->log($isi, "Penambahan");

        return redirect()
            ->route('jenispelanggan.index')
            ->with('success', 'Jenis pelanggan berhasil ditambahkan.');
    }

    /**
     * Update data jenis pelanggan.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'jenispelanggan_id' => 'required',
            'edit_jenispelanggan' => 'required|string|max:255',
        ]);

        try {
            $id = decrypt($validated['jenispelanggan_id']);
        } catch (\Exception $e) {
            return $request->ajax()
                ? Response::json(['success' => false, 'message' => 'ID tidak valid.'])
                : redirect()->route('jenispelanggan.index')->withErrors('ID tidak valid.');
        }

        $data = MJenisPelanggan::findOrFail($id);
        $data->update(['jenis_pelanggan' => $validated['edit_jenispelanggan']]);

        // if (method_exists($this, 'createlog') && Auth::check()) {
        //     $isi = Auth::user()->username . " mengubah jenis pelanggan {$data->jenis_pelanggan}.";
        //     $this->createlog($isi, "edit");
        // }

        $isi = auth()->user()->username . " telah mengubah jenis pelanggan " . $data->jenis_pelanggan . ".";
        $this->log($isi, "Pengubahan");

        return $request->ajax()
            ? Response::json(['success' => true, 'message' => 'Jenis pelanggan berhasil diperbarui.'])
            : redirect()->route('jenispelanggan.index')->with('success', 'Jenis pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus data jenis pelanggan.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'hapus_jenispelanggan_id' => 'required',
        ]);

        try {
            $id = decrypt($request->hapus_jenispelanggan_id);
        } catch (\Exception $e) {
            return $request->ajax()
                ? Response::json(['success' => false, 'message' => 'ID tidak valid.'])
                : redirect()->route('jenispelanggan.index')->withErrors('ID tidak valid.');
        }

        $data = MJenisPelanggan::findOrFail($id);
        $nama = $data->jenis_pelanggan;
        $data->delete();

        // if (method_exists($this, 'createlog') && Auth::check()) {
        //     $isi = Auth::user()->username . " menghapus jenis pelanggan {$nama}.";
        //     $this->createlog($isi, "delete");
        // }

        $isi = auth()->user()->username . " telah menghapus jenis pelanggan " . $nama . ".";
        $this->log($isi, "Penghapusan");

        return $request->ajax()
            ? Response::json(['success' => true, 'message' => 'Jenis pelanggan berhasil dihapus.'])
            : redirect()->route('jenispelanggan.index')->with('success', 'Jenis pelanggan berhasil dihapus.');
    }

    /**
     * API untuk select2 / pencarian jenis pelanggan.
     */
    public function jenispelanggancari()
    {
        $tags = MJenisPelanggan::select('id', 'jenis_pelanggan')->get();
        $formatted_tags = $tags->map(function ($tag) {
            return ['id' => $tag->id, 'text' => $tag->jenis_pelanggan];
        });
        return response()->json($formatted_tags);
    }
}
