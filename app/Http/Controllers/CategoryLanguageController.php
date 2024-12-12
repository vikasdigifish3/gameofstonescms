<?php

namespace App\Http\Controllers;

use App\Models\CategoryLanguage;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;

class CategoryLanguageController extends Controller
{
    public function index()
    {
        $categoryLanguages = CategoryLanguage::all();
        return view('category_language.index', compact('categoryLanguages'));
    }

public function create($categoryId)
{
    $category = Category::findOrFail($categoryId);
    $languages = Language::all();

    return view('category_language.create', compact('category', 'languages'));
}



public function store(Request $request, $categoryID)
{
    $validatedData = $request->validate([
        'names.*.language_id' => 'required|exists:languages,id',
        'names.*.name' => 'nullable',
    ]);

    foreach ($validatedData['names'] as $nameData) {
        if (!empty($nameData['name'])) {
            $categoryLanguage = new CategoryLanguage();
            $categoryLanguage->category_id = $categoryID;
            $categoryLanguage->language_id = $nameData['language_id'];
            $categoryLanguage->name = $nameData['name'];
            $categoryLanguage->save();
        }
    }

    return redirect()->route('categories.index')->with('success', 'Category languages created successfully.');
}


public function edit($categoryID)
{
    $category = Category::findOrFail($categoryID);
    $languages = Language::all();

    $categoryLanguages = CategoryLanguage::where('category_id', $categoryID)->get();

    return view('category_language.edit', compact('category', 'languages', 'categoryLanguages'));
}



public function update(Request $request, $categoryID)
{
    $category = Category::findOrFail($categoryID);
    $languages = Language::all();

    // Validate the request data
    $validatedData = $request->validate([
        'names.*' => 'nullable',
    ]);

    // Get the existing category languages for the category
    $existingLanguages = CategoryLanguage::where('category_id', $categoryID)->get();

    foreach ($languages as $language) {
        $languageID = $language->id;
        $name = isset($validatedData['names'][$languageID]) ? $validatedData['names'][$languageID] : null;

        // Find the existing category language for the language
        $categoryLanguage = $existingLanguages->where('language_id', $languageID)->first();

        if ($categoryLanguage) {
            // Update the existing category language if the name is provided
            if ($name !== null) {
                $categoryLanguage->name = $name;
                $categoryLanguage->save();
            } else {
                // Delete the category language if the name is not provided
                $categoryLanguage->delete();
            }
        } elseif ($name !== null) {
            // Create a new category language if it doesn't exist and a name is provided
            $newCategoryLanguage = new CategoryLanguage();
            $newCategoryLanguage->category_id = $categoryID;
            $newCategoryLanguage->language_id = $languageID;
            $newCategoryLanguage->name = $name;
            $newCategoryLanguage->save();
        }
    }

    return redirect()->route('categories.index')->with('success', 'Category languages updated successfully.');
}



 
    public function destroy(CategoryLanguage $categoryLanguage)
    {
        $categoryLanguage->delete();

        return redirect()->route('category_language.index')->with('success', 'Category Language deleted successfully');
    }
}
