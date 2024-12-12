<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Continent;
use App\Models\Country;
use App\Models\CountryContent;

class ContentCountryController extends Controller
{

/* 
public function edit(Content $content)
{
    $countries = Country::all();
    $attachedCountries = $content->countries;

    return view('contents.countries.edit', compact('content', 'countries', 'attachedCountries'));
}
*/
public function update(Request $request, Content $content)
{
    $content->countries()->sync($request->input('country_ids'));

    return redirect()->route('contents.editPortals', $content->id)->with('success', 'Countries updated successfully');
}

public function edit(Content $content)
{
    $countries = Country::all();
    $attachedCountries = $content->countries()->pluck('countries.id')->toArray();
    $continents = Continent::with('countries')->get();
    //print_r($continents);die();
    return view('contents.countries.edit', compact('content', 'countries', 'attachedCountries','continents'));
}

    //
}
