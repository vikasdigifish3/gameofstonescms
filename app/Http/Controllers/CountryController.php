<?php 
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Continent;
use App\Models\Content;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        $continents = Continent::all();
        return view('countries.create', compact('continents'));
    }

    public function store(Request $request)
    {
        $country = Country::create($request->all());
        return redirect()->route('countries.index');
    }

    public function edit(Country $country)
    {
        $continents = Continent::all();
        return view('countries.edit', compact('country', 'continents'));
    }

    public function update(Request $request, Country $country)
    {
        $country->update($request->all());
        return redirect()->route('countries.index');
    }
     public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('countries.index');
    }
   public function show($id)
    {
    $country = Country::findOrFail($id);

    // Retrieve all the contents along with their categories
    $contents = Content::with('categories')->get();

    // Group the contents by category
    $contentsByCategory = $contents->groupBy(function ($content) {
        // Get the first category name if available; otherwise, set a default name
        return $content->categories->name ?? 'Uncategorized';
    });
    $attachedContents = $country->contents()->pluck('contents.id')->toArray();

    return view('countries.show', compact('country', 'contentsByCategory','attachedContents'));
}   
}
?>