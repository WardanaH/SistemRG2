<?php

namespace App\Http\Controllers;

use App\Models\MCompany;
use App\Models\MTasks;
use Illuminate\Http\Request;

class MTasksController extends Controller
{
    public function index(Request $request, MCompany $company)
    {
        $company = MCompany::find($company->id);

        $tasks = MTasks::where('m_company_id', $company->id)->get();

        return view('companies.projects.task.index',
            compact('tasks', 'company')
        );
    }

    public function store(Request $request, MCompany $company)
    {
        $company = MCompany::find($company->id);

        MTasks::create([
            'm_company_id' => $company->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('task.index', $company->id);
    }

    public function update(Request $request, MCompany $company, MTasks $task)
    {
        $task = MTasks::find($task->id);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('task.index', $company->id);
    }

    public function destroy(Request $request, MCompany $company, MTasks $task)
    {
        $company = MCompany::find($company->id);
        $task = MTasks::find($task->id);

        $task->delete();

        return redirect()->route('task.index', $company->id);
    }
}
