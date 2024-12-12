<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Portal;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

//new index for filtering based on portal
    public function index()
    {
        $data = [];
        //$categories = $portal->categories()->where('parent_category', '0')->paginate(5);
        $categories = Category::where('parent_category', '0')->paginate(5);
        foreach ($categories as $category) {
            $data[] = [
                'category' => $category,
                'subcategories' => Category:: where('parent_category', $category->id)->get()
            ];
        }
        return view('categories.index', compact('data','categories'));
    }
    public function create()
    {

        $categories = Category :: All() -> where('parent_category', 0)->sortBy('name');

        return view('categories.create', compact( 'categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|numeric',

        ]);

        $category = new Category($request->all());
        $category->save();

         return redirect()->route('category_language.edit',$category->id);
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit( $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $categories = Category::where('parent_category', 0)->where('status', 1)->get();
        return view('categories.edit', compact('category',  'categories'));
    }
    public function update(Request $request,  $category_id)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|numeric',
        ]);

        $category = Category::find($category_id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();

        $return =urldecode($request->return) ;
            return redirect($return)->with('success', 'Category updated successfully.');

    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    public function allPortals( $id)
    {
        $category = Category::findOrFail($id);
        $portals = Portal :: all();
        $selectedPortals = $category->portals()->pluck('portals.id')->toArray();
        //$attachedContents = $country->contents()->pluck('contents.id')->toArray();
        return view('categories.portal.edit', compact('category',  'portals','selectedPortals'));
    }
    public function updatePortals(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $portals = $request->input('portals', []);
        $category->portals()->sync($portals);

        return redirect()->route('categories.index')->with('success', 'Portals updated successfully.');
    }
    public function multiAttach(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $portals = $request->input('portals', []);
        $category->portals()->sync($portals);

        return redirect()->back()->with('success', 'Portals attached successfully.');
    }

    public function multiDetach(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $portals = $request->input('portals', []);
        $category->portals()->detach($portals);

        return redirect()->back()->with('success', 'Portals detached successfully.');
    }
}

