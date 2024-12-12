<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        $portals = Portal::paginate(10);
        return view('portals.index', compact('portals'));
    }

    public function create()
    {
        return view('portals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|numeric',
        ]);

        Portal::create($request->all());
        return redirect()->route('portals.index')->with('success', 'Portal created successfully.');
    }

    public function show(Portal $portal)
    {
        return view('portals.show', compact('portal'));
    }

    public function edit(Portal $portal)
    {
        return view('portals.edit', compact('portal'));
    }

    public function update(Request $request, Portal $portal)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|numeric',
        ]);

        $portal->update($request->all());
        return redirect()->route('portals.index')->with('success', 'Portal updated successfully.');
    }

    public function destroy(Portal $portal)
    {
        $portal->delete();
        return redirect()->route('portals.index')->with('success', 'Portal deleted successfully.');
    }

   public function editCategories($id)
    {
        $portal = Portal::findOrFail($id);
        $categories = Category::all();
        $selectedCategories = $portal->categories()->pluck('categories.id')->toArray();

        return view('portals.editCategories', compact('portal', 'categories', 'selectedCategories'));
    }
    public function updateCategories(Request $request, $id)
    {
        $portal = Portal::findOrFail($id);
        $categories = $request->input('categories', []);

        $portal->categories()->sync($categories);

        return redirect()->route('portals.editCategories', $portal->id)
            ->with('success', 'Categories updated successfully');
    }

    public function editContents($portalId)
    {
        $portal = Portal::findOrFail($portalId);
        $attachedCategories = $portal ->categories()->pluck("categories.id")->toArray();
        $contents = Content::with('categories')->whereHas('categories', function ($query) use ($attachedCategories) {
            $query->whereIn('categories.id', $attachedCategories);
            })->get();
        
        // Group the contents by category
        $contentsByCategory = $contents->groupBy(function ($content) {
            // Get the first category name if available; otherwise, set a default name
            return $content->categories->name ?? 'Uncategorized';
        });
        $attachedContents = $portal->contents()->pluck('contents.id')->toArray();

        return view('portals.edit-contents', compact('portal', 'contents', 'attachedContents','contentsByCategory'));
    }

    public function attachContents(Request $request, $portalId)
    {
        $portal = Portal::findOrFail($portalId);
        $contents = $request->input('contents', []);

        // Attach contents to the portal
        $portal->contents()->sync($contents);

        // Attach content categories to the portal
        //$categoryIds = Content::whereIn('id', $contents)->pluck('category_id')->toArray();
        //$portal->categories()->sync($categoryIds);

        return redirect()->route('portals.show', $portal->id)->with('success', 'Contents attached successfully');
    }
    public function updateContents($id)
    {
        $portal = Portal::findOrFail($id);
        
        // Retrieve the selected content IDs from the form
        $selectedContents = request()->input('content_ids', []);

        // Sync the selected contents with the portal
        $portal->contents()->sync($selectedContents);

        return redirect()->route('portals.editContents', $portal->id)
            ->with('success', 'Contents updated successfully.');
    }

    public function multiAttachContents($id)
    {
        $portal = Portal::findOrFail($id);
        
        // Retrieve the selected content IDs from the form
        $selectedContents = request()->input('contents', []);

        // Attach the selected contents to the portal
        $portal->contents()->attach($selectedContents);

        return redirect()->route('portals.editContents', $portal->id)
            ->with('success', 'Contents attached successfully.');
    }

    // Detach multiple contents from a portal
    public function multiDetachContents($id)
    {
        $portal = Portal::findOrFail($id);
        
        // Retrieve the selected content IDs from the form
        $selectedContents = request()->input('contents', []);

        // Detach the selected contents from the portal
        $portal->contents()->detach($selectedContents);

        return redirect()->route('portals.editContents', $portal->id)
            ->with('success', 'Contents detached successfully.');
    }
}

