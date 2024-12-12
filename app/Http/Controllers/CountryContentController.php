<?php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Content;
use Illuminate\Http\Request;

class CountryContentController extends Controller
{
    public function attach(Request $request, Country $country)
    {
        $contentId = $request->input('content_id');

        $country->contents()->attach($contentId);

        return redirect()->route('countries.show', $country->id)
            ->with('success', 'Content attached successfully');
    }

    public function detach(Request $request, Country $country)
    {
        $contentId = $request->input('content_id');

        $country->contents()->detach($contentId);

        return redirect()->route('countries.show', $country->id)
            ->with('success', 'Content detached successfully');
    }
    public function attachMultiple(Request $request, Country $country)
    {
        $contentIds = $request->input('content_ids');

        $country->contents()->sync($contentIds);

        return redirect()->route('countries.show', $country->id)
            ->with('success', 'Contents attached successfully');
    }
}
