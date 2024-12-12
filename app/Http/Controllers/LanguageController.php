<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Portal;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language :: paginate(5);
        return view('languages.index', compact('languages'));
    }

    public function create(Portal $portal)
    {
        return view('languages.create', compact('portal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'shortcode' => 'required',
            'status' => 'required|numeric',
        ]);

        $language = new Language($request->all());
        $language->save();

        return redirect()->route('languages.index')->with('success', 'Language created successfully.');
    }

    public function show(Language $language)
    {
        return view('languages.show', compact('language'));
    }

    public function edit( Language $language)
    {
        return view('languages.edit', compact('language'));
    }

    public function update(Request $request,  Language $language)
    {
        $request->validate([
            'name' => 'required',
            'shortcode' => 'required',
            'status' => 'required|numeric',
        ]);

        $language->update($request->all());
        return redirect()->route('languages.index')->with('success', 'Language updated successfully.');
    }

    public function destroy( Language $language)
    {
        $language->delete();
        return redirect()->route('languages.index')->with('success', 'Language deleted successfully.');
    }
}
