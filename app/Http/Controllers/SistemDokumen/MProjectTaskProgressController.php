<?php

namespace App\Http\Controllers;

use App\Models\MProjects;
use Illuminate\Http\Request;
use App\Models\MProjectTaskProgress;

class MProjectTaskProgressController extends Controller
{
    public function progress(MProjects $project)
    {
        $progress = MProjectTaskProgress::with('mTask')
            ->where('m_project_id', $project->id)
            ->get();

        return view('companies.projects.progress.index', compact(
            'progress',
            'project'
        ));
    }

    public function uploadBukti(Request $request, MProjectTaskProgress $progress)
    {
        $request->validate([
            'file_bukti' => 'required|file|mimes:jpg,png,pdf',
        ]);

        $file = $request->file('file_bukti');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/bukti'), $filename);

        $progress->update([
            'file_bukti' => $filename
        ]);

        return back()->with('success', 'Bukti berhasil diupload.');
    }

    public function updateStatus(Request $request, MProjectTaskProgress $progress)
    {
        $request->validate([
            'status_progress' => 'required|string',
        ]);

        // Update status task
        $progress->status_progress = $request->input('status_progress');
        $progress->save();

        // ==== CEK SEMUA TASK ====
        $projectId = $progress->m_project_id;

        if ($this->cekSemuaTaskSelesai($projectId)) {
            MProjects::where('id', $projectId)->update([
                'status' => 'Selesai'
            ]);
        } else {
            // Jika ada yang pending, project kembali pending
            MProjects::where('id', $projectId)->update([
                'status' => 'Pending'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status' => $progress->status_progress,
            'project_id' => $projectId,
        ]);
    }

    private function cekSemuaTaskSelesai($projectId)
    {
        $totalTask = MProjectTaskProgress::where('m_project_id', $projectId)->count();
        $taskSelesai = MProjectTaskProgress::where('m_project_id', $projectId)
            ->where('status_progress', 'Selesai')
            ->count();

        return $totalTask > 0 && $totalTask == $taskSelesai;
    }


    public function update(Request $request, MProjectTaskProgress $progress)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $progress->notes = $request->input('notes');
        $progress->save();

        return response()->json([
            'success' => true,
            'message' => 'Notes updated',
            'notes' => $progress->notes,
            'progress_id' => $progress->id,
        ]);
    }
}
