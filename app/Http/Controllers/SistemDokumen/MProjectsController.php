<?php

namespace App\Http\Controllers\SistemDokumen;

use App\Http\Controllers\Controller;
use App\Models\MTasks;
use App\Models\MCompany;
use App\Models\MProjects;
use Illuminate\Http\Request;
use App\Models\MProjectTaskProgress;

class MProjectsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'value_projects' => 'required|numeric',
            'company_id' => 'required|exists:m_companies,id',
        ]);

        // 1. Buat project
        $project = MProjects::create([
            'name' => $request->name,
            'description' => $request->description,
            'value_projects' => $request->value_projects,
            'm_company_id' => $request->company_id,
        ]);

        // 2. Ambil semua task perusahaan terkait
        $tasks = MTasks::where('m_company_id', $request->company_id)->get();

        // 3. Buat progress untuk setiap task
        foreach ($tasks as $task) {
            MProjectTaskProgress::create([
                'm_project_id' => $project->id,
                'm_task_id'    => $task->id,
                'status_progress'       => 'Pending',       // atau null sesuai kebutuhan kamu
                'value'        => null,            // field lain sesuaikan migration
            ]);
        }

        return redirect()
            ->route('companies.show', $request->company_id)
            ->with('success', 'Proyek dan task progress berhasil ditambahkan.');
    }

    public function destroy(MProjects $project)
    {
        // hapus semua progress yang terkait
        MProjectTaskProgress::where('m_project_id', $project->id)->delete();

        // hapus project
        $project->delete();

        return redirect()
            ->route('companies.show', $project->mCompany->id)
            ->with('success', 'Proyek dan semua progress berhasil dihapus.');
    }

    public function update(Request $request, MProjects $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'value_projects' => 'required|numeric',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'value_projects' => $request->value_projects,
        ]);

        return redirect()
            ->route('companies.show', $project->mCompany->id)
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function uploadProof(Request $request, MProjects $project)
    {
        $request->validate([
            'proof_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // upload file
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $path = $file->store('proofs', 'public');

            $project->file_bukti = $path;
            $project->paid_status = 'Lunas'; // otomatis jadi lunas
            $project->save();
        }

        return back()->with('success', 'File bukti berhasil diupload dan status pembayaran diubah menjadi Lunas.');
    }
}
