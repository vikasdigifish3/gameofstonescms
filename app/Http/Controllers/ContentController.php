<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use App\Models\LanguageContent;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Portal;
use App\Models\Language;
use Illuminate\Http\Response;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use GuzzleHttp\Client;
use Kreait\Firebase\Factory;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function uploadMediaToImageKit($media, $folderPath, $fileType)
    {
        $client = new Client();
        $url = config('services.imagekit.upload_endpoint');

        // Define the filename based on the fileType
        $fileName = $fileType . '.' . time() . '.' . $media->getClientOriginalExtension();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(config('services.imagekit.private_key') . ':'),
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($media->getPathname(), 'r'),
                        'filename' => $fileName,
                    ],
                    [
                        'name' => 'fileName',
                        'contents' => $fileName,
                    ],
                    [
                        'name' => 'folder',
                        'contents' => $folderPath,
                    ],
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            return [
                'url' => $body['url'],
                'fileId' => $body['fileId']
            ];        } catch (\Exception $e) {
            Log::error("Failed to upload to ImageKit: " . $e->getMessage());
            return null;
        }
    }

    protected function deleteMediaFromImageKit($fileId)
    {
        $client = new Client();
        $url = "https://api.imagekit.io/v1/files/" . $fileId;

        try {
            $response = $client->request('DELETE', $url, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(config('services.imagekit.private_key') . ':'),
                ]
            ]);

            if ($response->getStatusCode() == 204) {
                // Success, file deleted
                Log::info("File deleted successfully from ImageKit: " . $fileId);
            } else {
                // Handle unexpected response
                Log::warning("Unexpected response received from ImageKit during deletion: " . $response->getBody());
            }
        } catch (\Exception $e) {
            Log::error("Failed to delete from ImageKit: " . $e->getMessage());
            // Handle failure appropriately
        }
    }

    public function index(Request $request)
    {
        // columns that are sortable
        $sortableColumns = [
            'id',
            'name',
            'category_id',
            'language_id',
            'status'
        ];
        // get the column to sort by, default to 'created_at'
        $sort_by = $request->get('sort_by', 'created_at');
        // get the sort direction, default to 'desc'
        $sort_order = $request->get('sort_order', 'desc');

        // if the requested column is not sortable, fall back to 'created_at'
        if (!in_array($sort_by, $sortableColumns)) {
            $sort_by = 'created_at';
        }

        //search query mechanism
        $searchQuery = $request->get('search', '');
        $portal_id = $request->get('portal', 0);
        $selected_content_type = $request->get('content_type', '');
        $download = $request->get('download', 0);

        if ($download == 1) {
            //write code for downloadng
            $data_to_download = [];
            if ($portal_id > 0) {
                $portals[] = Portal::findOrFail($portal_id);
            } else {
                $portals = Portal::all();
            }
            foreach ($portals as $portal) {
                $name = $portal->name;
                $data_to_download[] = [$name];
                $parent_categories = $portal->categories()->where('parent_category', 0)->get();
                foreach ($parent_categories as $parent_category) {
                    $name = $parent_category->name;
                    $data_to_download[] = ['', $name];
                    $child_categories = $portal->categories()->where('parent_category', $parent_category->id)->get();
                    foreach ($child_categories as $child_category) {
                        $name = $child_category->name;
                        $data_to_download[] = ['', '', $name];
                        $contents = $portal->contents()->where('category_id', $child_category->id)->pluck('name');
                        foreach ($contents as $value) {
                            $data_to_download[] = ['', '', '', $value];
                        }

                    }
                }
            }
            $csvContent = '';
            foreach ($data_to_download as $row) {
                $csvContent .= implode(',', $row) . "\n";
            }
            //print_r($data_to_download); die();
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="data.csv"',
            ];

            // Create the response object with CSV content and headers
            $response = new Response($csvContent, 200, $headers);

            return $response;
        }

        $portals = Portal::all();
        if ($portal_id > 0) {
            $portal = Portal::findOrFail($portal_id);
            $query = $portal->contents();
        } else {
            $query = new Content();
        }
        if (!empty($searchQuery)) {
            $query = $query->where('name', 'like', '%' . $searchQuery . '%');
        }
        // Filter by selected content type
        if (!empty($selected_content_type)) {
            $query = $query->where('content_type', $selected_content_type);
        }
        $contents = $query->with(['translations'])
//            ->distinct()
            ->orderBy($sort_by, $sort_order)
            ->paginate(10)
            ->appends(['search' => $searchQuery, 'sort_by' => $sort_by, 'sort_order' => $sort_order]);

//        dd($contents);
        $contents->withPath('?portal=' . $portal_id);

        // prepare sortable column headers for the view
        // print_r($contents); die();
        $sortableHeaders = [];
        foreach ($sortableColumns as $column) {
            $new_sort_order = ($sort_by === $column && $sort_order === 'asc') ? 'desc' : 'asc';
            $sortableHeaders[$column] = route('contents.index', [

                'sort_by' => $column,
                'sort_order' => $new_sort_order,
                'portal' => $portal_id,
                'search' => $searchQuery,
            ]);
        }
        $sortableHeaders['download'] = route('contents.index', [
            'portal' => $portal_id,
            'download' => 1,
        ]);
        // Fetch distinct content types from the database
        $content_types = Content::select('content_type')->distinct()->pluck('content_type')->all();


        return view('contents.index', compact('contents', 'sort_by', 'sort_order', 'sortableHeaders', 'portals', 'searchQuery', 'portal_id', 'content_types', 'selected_content_type'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)
            ->orderBy('parent_category')
            ->orderBy('name')
            ->get();

        $languages = Language::all();
        return view('contents.create', compact('categories', 'languages'));
    }

    public function show(Content $content)
    {
        $categories = Category::all();
        return view('contents.show', compact('content', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'short_title' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'description' => 'nullable|string',
            'status' => 'required|numeric',
            'is_featured' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:50480',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:50480',
            'file_path' => 'nullable|file|mimes:mp4,pdf,jpeg,png,jpg,gif,webp,mp3,wav|max:1024000',
            'remote_file_path' => 'nullable|url',
            'content_type' => 'required|in:video,infographic,article', //file type
            'either_file_or_url' => Rule::requiredIf(function () use ($request) {
                return !$request->hasFile('file_path') && !$request->filled('remote_file_path');
            }),
        ]);

        // Initial variables
        $thumbnail_path = null;
        $banner_path = null;
        $file_path = null;
        $thumbnail_file_id = null;
        $banner_file_id = null;
        $file_file_id = null;

        // Get category and parent category names
        $category = Category::find($request->category_id);
        $parent_category = Category::find($category->parent_category);

        // Folder path for ImageKit
        $folderPath = '/' . Str::slug($parent_category->name) . '/' . Str::slug($category->name) . '/' . Str::slug($request->name);

        // For the thumbnail
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $result = $this->uploadMediaToImageKit($request->file('thumbnail'), $folderPath, 'thumb');
            if ($result) {
                $thumbnail_path = $result['url'];
                $thumbnail_file_id = $result['fileId'];
            }
        }

        // For the banner
        if ($request->hasFile('banner') && $request->file('banner')->isValid()) {
            $result = $this->uploadMediaToImageKit($request->file('banner'), $folderPath, 'banner');
            if ($result) {
                $banner_path = $result['url'];
                $banner_file_id = $result['fileId'];
            }
        }

        // For the main content file
        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            $result = $this->uploadMediaToImageKit($request->file('file_path'), $folderPath, 'file');
            if ($result) {
                $file_path = $result['url'];
                $file_file_id = $result['fileId'];
            }
        }

        // Create a new Content instance with the validated data and file paths
        $content_new = new Content([
            'category_id' => $request->category_id,
            'language_id' => 1, // Ensure this is correctly set or dynamically determined
            'name' => $request->name,
            'short_title' => $request->short_title,
            'description' => $request->description,
            'status' => (int)$request->status,
            'is_featured' => ($request->is_featured != null ? 1 : 0),
            'thumb_url' => $thumbnail_path,
            'thumb_file_id' => $thumbnail_file_id,
            'banner_url' => $banner_path,
            'banner_file_id' => $banner_file_id,
            'file_path' => $file_path,
            'file_file_id' => $file_file_id,
            'content_type' => $request->content_type,

        ]);

        // Handle remote file path separately if provided
        if (!$request->hasFile('file_path') && $request->filled('remote_file_path')) {
            $content_new->remote_file_path = $request->remote_file_path;
        }

        // Save the new Content instance to the database
        $content_new->save();

        if ($request->has('tags')) {
            $content_new->tags()->sync($request->tags);
        }
        // Redirect to the content index page with a success message
        return redirect()->route('languageContents.create', ['content' => $content_new->id])->with('success', 'Content created successfully.');
    }

    public function edit(Content $content)
    {
        $categories = Category::where('status', 1)
            ->orderBy('parent_category')
            ->orderBy('name')
            ->get();

        $languages = Language::all();

        return view('contents.edit', compact('content', 'categories', 'languages'));
    }

    public function update(Request $request, Portal $portal, Content $content)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'short_title' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'description' => 'nullable|string',
            'status' => 'required|numeric',
            'is_featured' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:50480',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:50480',
            'file_path' => 'nullable|file|mimes:mp4,pdf,jpeg,png,jpg,gif,webp,mp3,wav|max:1024000',
            'remote_file_path' => 'nullable',
            'content_type' => 'required|in:video,infographic,article', //file type
            'either_file_or_url' => Rule::requiredIf(function () use ($request) {
                return !$request->hasFile('file_path') && !$request->filled('remote_file_path');
            }),
        ]);

        // Get category and parent category names
        $category = Category::find($request->category_id);
        $parent_category = Category::find($category->parent_category);

        // Folder path for ImageKit
        $folderPath = '/' . Str::slug($parent_category->name) . '/' . Str::slug($category->name) . '/' . Str::slug($request->name);

        // For the thumbnail
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            // Delete old thumbnail file from ImageKit
            if (!empty($content->thumb_file_id)) {
                $this->deleteMediaFromImageKit($content->thumb_file_id);
            }

            // Upload new thumbnail
            $result = $this->uploadMediaToImageKit($request->file('thumbnail'), $folderPath, 'thumb');
            if ($result) {
                $content->thumb_url = $result['url']; // Update the content thumbnail URL
                $content->thumb_file_id = $result['fileId']; // Store new fileId in database
            }
        }

// For the banner
        if ($request->hasFile('banner') && $request->file('banner')->isValid()) {
            // Delete old banner file from ImageKit
            if (!empty($content->banner_file_id)) {
                $this->deleteMediaFromImageKit($content->banner_file_id);
            }

            // Upload new banner
            $result = $this->uploadMediaToImageKit($request->file('banner'), $folderPath, 'banner');
            if ($result) {
                $content->banner_url = $result['url']; // Update the content banner URL
                $content->banner_file_id = $result['fileId']; // Store new fileId in database
            }
        }


        // Handle the uploaded main file
        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            // Delete old main file from ImageKit
            if (!empty($content->file_file_id)) {
                $this->deleteMediaFromImageKit($content->file_file_id);
            }

            // Upload new main file
            $result = $this->uploadMediaToImageKit($request->file('file_path'), $folderPath, 'file');
            if ($result) {
                $content->file_path = $result['url']; // Update the content file path with the new URL
                $content->file_file_id = $result['fileId']; // Store new fileId in the database
                $content->remote_file_path = null; // Clear any existing remote file path
            }
        } elseif ($request->filled('remote_file_path')) {
            // If a remote file URL is provided, update accordingly
            $content->remote_file_path = $request->remote_file_path;
            $content->file_path = null; // Clear any existing local file path
            // Consider if you need to delete the old file from ImageKit if moving from a local file to a remote URL
            if (!empty($content->file_file_id)) {
                $this->deleteMediaFromImageKit($content->file_file_id);
                $content->file_file_id = null; // Clear the fileId since the local file is no longer used
            }
        }


        // Update other content fields
        $content->category_id = $request->category_id;
        $content->name = $request->name;
        $content->description = $request->description;
        $content->status = (int)$request->status;
        $content->is_featured = $request->is_featured ? 1 : 0;
        $content->content_type = $request->content_type;
        $content->short_title = $request->short_title;
        $content->update($request->except(['tags']));

        // Save the updated content
        $content->save();
        if ($request->has('tags')) {
            $content->tags()->sync($request->tags);
        } else {
            $content->tags()->detach();
        }
        $return = urldecode($request->return);
        return redirect($return)->with('success', 'Content updated successfully.');
    }

    public function destroy(Portal $portal, Content $content)
    {
        $content->delete();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    }

    public function editPortals($id)
    {
        $content = Content::findOrFail($id);
        $portals = Portal::all();
        $attachedPortals = $content->portals()->pluck('portals.id')->toArray();

        return view('contents.edit-portals', compact('content', 'portals', 'attachedPortals'));
    }

    // Update portals for a content
    public function updatePortals($id)
    {
        $content = Content::findOrFail($id);

        // Retrieve the selected portal IDs from the form
        $selectedPortals = request()->input('portals', []);

        // Sync the selected portals with the content
        $content->portals()->sync($selectedPortals);

        return redirect()->route('contents.show', $content->id)
            ->with('success', 'Portals updated successfully.');
    }
}
