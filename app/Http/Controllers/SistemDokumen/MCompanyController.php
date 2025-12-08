<?php

namespace App\Http\Controllers;

use App\Models\MCompany;
use App\Models\MProjects;
use Illuminate\Http\Request;

class MCompanyController extends Controller
{
    public function index()
    {
        $companies = MCompany::all();

        return view('companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:m_companies',
            'descriptions' => 'required|string',
        ]);
        // dd($request->all());

        MCompany::create([
            'name' => $request->name,
            'email' => $request->email,
            'descriptions' => $request->descriptions,
        ]);

        return redirect()->route('companies.index')->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    public function show(MCompany $company)
    {
        $company = MCompany::find($company->id);
        $projects = MProjects::where('m_company_id', $company->id)->get();

        return view('companies.projects.index', compact('projects', 'company'));
    }

    public function edit(MCompany $company)
    {
        $company = MCompany::find($company->id);

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, MCompany $company)
    {
        $company = MCompany::find($company->id);

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'descriptions' => $request->descriptions,
        ]);

        return redirect()->route('companies.index')->with('success', 'Perusahaan berhasil diperbarui.');
    }

    public function destroy(MCompany $company)
    {
        $company = MCompany::find($company->id);

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Perusahaan berhasil dihapus.');
    }
}
