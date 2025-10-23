<?php

namespace App\Http\Controllers;

use App\Models\MPelanggans;
use Illuminate\Http\Request;
use App\Models\MJenisPelanggan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PelanggansController extends Controller
{
    public function index()
    {
        $jenispelanggans = MJenisPelanggan::all();
        return view('admin.pelanggan.index', compact('jenispelanggans'));
    }

    public function show($id)
    {
        try {
            // 🔹 Dekripsi ID dari tombol edit
            $id = decrypt($id);

            $pelanggan = MPelanggans::findOrFail($id);

            return response()->json($pelanggan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    // ✅ Load data pelanggan (DataTables)
    public function getData()
    {
        $pelanggans = MPelanggans::with('jenisPelanggan')
            ->select('pelanggans.*');

        return DataTables::of($pelanggans)
            ->addColumn('jenis_pelanggan', fn($p) => $p->jenisPelanggan->jenis_pelanggan ?? '-')
            ->addColumn('status_pelanggan', function ($p) {
                return $p->status_pelanggan
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->addColumn('limit_pelanggan', fn($p) => 'Rp ' . number_format($p->limit_pelanggan, 0, ',', '.'))
            ->addColumn('action', function ($p) {
                $id = encrypt($p->id);
                return '
                    <button class="btn btn-success btn-sm editBtn" data-id="' . $id . '">✏️</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="' . $id . '">🗑️</button>
                ';
            })
            ->rawColumns(['status_pelanggan', 'action'])
            ->make(true);
    }

    // ✅ Simpan pelanggan baru
    public function store(Request $request)
    {
        $rules = [
            'tambah_jenis_pelanggan' => 'required',
            'tambah_namapemilik' => 'required',
            'tambah_ktppelanggan' => 'required|numeric',
            'tambah_hppelanggan' => 'required|numeric',
            'tambah_namaperusahaan' => 'required',
            'tambah_teleponpelanggan' => 'nullable|numeric',
            'tambah_emailpelanggan' => 'required|email',
            'tambah_alamatpelanggan' => 'required',
            'tambah_limittagihan' => 'required|numeric',
            'tambah_rekpelanggan' => 'required|numeric',
            'tambah_keterangan' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $pelanggan = MPelanggans::create([
            'jenispelanggan_id' => decrypt($request->tambah_jenis_pelanggan),
            'nama_pemilik' => $request->tambah_namapemilik,
            'ktp' => $request->tambah_ktppelanggan,
            'hp_pelanggan' => $request->tambah_hppelanggan,
            'nama_perusahaan' => $request->tambah_namaperusahaan,
            'telpon_pelanggan' => $request->tambah_teleponpelanggan,
            'email_pelanggan' => $request->tambah_emailpelanggan,
            'alamat_pelanggan' => $request->tambah_alamatpelanggan,
            'tempo_pelanggan' => $request->tambah_tempotagihan,
            'limit_pelanggan' => $request->tambah_limittagihan,
            'norek_pelanggan' => $request->tambah_rekpelanggan,
            'keterangan_pelanggan' => $request->tambah_keterangan,
            'status_pelanggan' => $request->tambah_statuspelanggan,
            'user_id' => Auth::id(),
        ]);

        if ($pelanggan) {
            return response()->json("Success");
        }
        return response()->json("Failed");
    }

    // ✅ Update pelanggan
    public function update(Request $request)
    {
        try {
            // Ambil pelanggan berdasarkan ID terenkripsi
            $pelanggan = MPelanggans::findOrFail(decrypt($request->pelanggan_id));

            // Validasi hanya field yang ada di modal edit
            $rules = [
                'edit_namaperusahaan' => 'required|string|max:255',
                'edit_namapemilik' => 'required|string|max:255',
                'edit_teleponpelanggan' => 'nullable|numeric',
                'edit_emailpelanggan' => 'required|email',
                'edit_limittagihan' => 'required|numeric',
                'edit_keterangan' => 'nullable|string',
                'edit_statuspelanggan' => 'required|in:0,1',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()]);
            }

            // Update data pelanggan
            $pelanggan->update([
                'nama_perusahaan' => $request->edit_namaperusahaan,
                'nama_pemilik' => $request->edit_namapemilik,
                'telpon_pelanggan' => $request->edit_teleponpelanggan,
                'email_pelanggan' => $request->edit_emailpelanggan,
                'limit_pelanggan' => $request->edit_limittagihan,
                'keterangan_pelanggan' => $request->edit_keterangan,
                'status_pelanggan' => $request->edit_statuspelanggan,
            ]);

            return response()->json("Success");
        } catch (\Exception $e) {
            // Kamu bisa log error untuk debugging
            Log::error('Update pelanggan gagal: '.$e->getMessage());
            return response()->json("Failed");
        }
    }

    // ✅ Hapus pelanggan
    public function destroy(Request $request)
    {
        $pelanggan = MPelanggans::findOrFail(decrypt($request->hapus_pelanggan_id));
        $pelanggan->delete();

        return response()->json("Success");
    }
}
