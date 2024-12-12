<?php
// ContinentController.php
namespace App\Http\Controllers;

use App\Models\Continent;
use Illuminate\Http\Request;

class ContinentController extends Controller
{
    public function index()
    {
        $continents = Continent::all();
        return view('continents.index', compact('continents'));
    }

    public function create()
    {
        return view('continents.create');
    }

    public function store(Request $request)
    {
        $continent = Continent::create($request->all());
        return redirect()->route('continents.index');
    }

    public function edit($id)
    {
        $continent = Continent::find($id);
        return view('continents.edit', compact('continent'));
    }

    public function update(Request $request, Continent $continent)
    {
        $continent->update($request->all());
        return redirect()->route('continents.index');
    }
    public function destroy(Continent $continent)
    {
        $continent->delete();
        return redirect()->route('continents.index');
    }
}
?>