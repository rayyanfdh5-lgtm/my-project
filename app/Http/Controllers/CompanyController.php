<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();

        return view('admin.pages.settings', compact('company'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tax_number' => 'nullable|string|max:50',
            'business_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_email' => 'nullable|email|max:255',
        ]);

        $company = Company::first();

        if (! $company) {
            $company = new Company;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $logo = $request->file('logo');
            $logoPath = $logo->store('company', 'public');
            $validated['logo'] = $logoPath;
        }

        $company->fill($validated);
        $company->save();

        return back()->with('success', 'Informasi instansi berhasil diperbarui.');
    }
}
