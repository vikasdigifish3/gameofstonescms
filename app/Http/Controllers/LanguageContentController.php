<?php

namespace App\Http\Controllers;

use App\Models\LanguageContent;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Language;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class   LanguageContentController extends Controller
{

    public function create(Content $content)
    {
        $languages = Language::all();

        return view('languagecontent.create', compact('content', 'languages'));
    }


    public function store(Request $request, $contentId)
    {
        $content = Content::findOrFail($contentId);
        $category = Category:: findOrFail($content->category_id);
        $parent_category = Category:: findOrFail($category->parent_category);
        //print_r($category);die();
        $category_dir = 'public/'. Str::slug($parent_category->name) . '/' . Str::slug($category->name);
        // Process language contents for each language
//        dd($request['languages']);
        foreach ($request['languages'] as $index => $language) {
            $languageContent = new LanguageContent();
            $languageContent->content_id = $contentId;
            $languageContent->language_id = $language;
            $languageContent->name = $request->names[$index];
            $languageContent->short_title = $request->short_titles[$index] ?? null;
            $languageContent->description = $request->descriptions[$index] ?? null;
            $lang_dir = $category_dir ."/".$language ;
            // Upload and store the thumbnail image
            if ($request->hasFile('thumbs') && $request->file('thumbs')[$index]->isValid()) {
                $thumbnail = $request->file('thumbs')[$index];
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
                $languageContent->thumb_url = Storage::url($thumbnailPath);
            }

            // Upload and store the banner image
            if ($request->hasFile('banners') && $request->file('banners')[$index]->isValid()) {
                $banner = $request->file('banners')[$index];
                $bannerPath = $banner->store('banners', 'public');
                $languageContent->banner_url = Storage::url($bannerPath);
            }

            // Upload and store the file
            if ($request->hasFile('files') && $request->file('files')[$index]->isValid()) {
                $file = $request->file('files')[$index];
                $filePath = $file->store('files', 'public');
                $languageContent->file_path = Storage::url($filePath);
            }

            // Save the language content if at least one field is provided
            if ($languageContent->name || $languageContent->description || $languageContent->thumb_url || $languageContent->banner_url || $languageContent->file_path) {
                $languageContent->save();
            }
        }

        return redirect()->route('contents.countries.edit',['content' => $contentId])->with('success', 'Language contents created successfully.');
    }



    public function edit($contentId)
    {

        // Retrieve the content and its language contents from the database
        $content = Content::findOrFail($contentId);
        $languageContents = LanguageContent::where('content_id', $contentId)->get();

        // Retrieve the list of all languages
        $languages = Language::all();

        // Store the selected languages' IDs in an array
        $selectedLanguages = $languageContents->pluck('language_id')->toArray();

        // Create an array to store the language content data
        $alldata = [];

        foreach ($languageContents as $languageContent) {
            $languageId = $languageContent->language_id;
            $alldata[$languageId]['name'] = $languageContent->name;
            $alldata[$languageId]['short_title'] = $languageContent->short_title;
            $alldata[$languageId]['description'] = $languageContent->description;
            $alldata[$languageId]['thumb_url'] = $languageContent->thumb_url;
            $alldata[$languageId]['banner_url'] = $languageContent->banner_url;
            $alldata[$languageId]['file_path'] = $languageContent->file_path;
        }
        return view('languagecontent.edit', compact('content','contentId', 'languages', 'selectedLanguages', 'alldata'));
    }


    public function update(Request $request, $contentId)
    {

        $validatedData = $request->validate([
            'names.*' => 'nullable|string',
            'short_titles.*' => 'nullable|string',
            'descriptions.*' => 'nullable|string',
            'thumbs.*' => 'nullable|image',
            'banners.*' => 'nullable|image',
            'files.*' => 'nullable|file',
        ]);

        $content = Content::findOrFail($contentId);
        foreach ($request['languages'] as $index => $languageId) {
            $languageContent = LanguageContent::where('content_id', $contentId)
                ->where('language_id', $languageId)
                ->first();
            if(empty($validatedData['names'][$index])){
                if($languageContent){
                    $languageContent->delete();
                }
                continue ;
            }

            if (!$languageContent) {
                $languageContent = new LanguageContent();
                $languageContent->content_id = $contentId;
                $languageContent->language_id = $languageId;
            }

            $languageContent->name = $validatedData['names'][$index];
            $languageContent->short_title = $request->short_titles[$languageId] ?? null;

            $languageContent->description = $validatedData['descriptions'][$index];

            // Handle optional files
            $thumbs = $request->file('thumbs');
            if ($thumbs && isset($thumbs[$index]) && $thumbs[$index]->isValid()) {
                $thumb = $thumbs[$index];
                $thumbPath = $thumb->store('thumbnails');
                $languageContent->thumb_url = $thumbPath;
            }

            $banners = $request->file('banners');
            if ($banners && isset($banners[$index]) && $banners[$index]->isValid()) {
                $banner = $banners[$index];
                $bannerPath = $banner->store('banners');
                $languageContent->banner_url = $bannerPath;
            }

            $files = $request->file('files');
            if ($files && isset($files[$index]) && $files[$index]->isValid()) {
                $file = $files[$index];
                $filePath = $file->store('files');
                $languageContent->file_path = $filePath;
            }

            $languageContent->save();
        }

        $return = urldecode($request->return);
        return redirect($return)->with('success', 'Language Contents updated successfully.');    }

}
