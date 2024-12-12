@extends('layouts.portal')

@section('content')
   <style>
       /* Style the page title */
       h1 {
           font-size: 2.5rem;
           margin-top: 0;
       }

       /* Style the section titles */
       h2 {
           font-size: 2rem;
           margin-top: 2rem;
       }

       /* Style the cards */
       .card {
           border: none;
           box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
       }

       .card-title {
           font-size: 1.2rem;
       }

       .card-text {
           font-size: 1.5rem;
           font-weight: bold;
       }

       /* Style the tables */
       .table th,
       .table td {
           padding: 0.75rem;
           vertical-align: middle;
           border-top: 1px solid #dee2e6;
       }

       .table th {
           font-weight: bold;
           background-color: #f5f5f5;
       }

       /* Style the rows */
       .table tbody tr:hover {
           background-color: #f5f5f5;
       }

       /* Style the cells */
       .table td {
           font-size: 1.2rem;
       }

   </style>
    <div class="row mt-4">
        <div class="col">
            <h1>Welcome to the Dashboard</h1>
        </div>
    </div>

    <!-- Summary section -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text">{{ App\Models\Category::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Contents</h5>
                    <p class="card-text">{{ App\Models\Content::count() }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Contents</h5>
                    <p class="card-text">{{ App\Models\Content::where('status', 1)->count() }}</p>
                    <h5 class="card-title">Inactive Contents</h5>
                    <p class="card-text">{{ App\Models\Content::where('status', 0)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Portal table -->
    <div class="row mt-4">
        <div class="col">
            <h2>Latest Portal</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach(App\Models\Portal::latest()->take(5)->get() as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if($category->parent_category == 0)
                                Top Level
                            @else
                                {{ App\Models\Portal::find($category->parent_category)->name }}
                            @endif
                        </td>
                        <td>{{ $category->created_at ? $category->created_at->format('d M, Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col">
            <h2>Latest Categories</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach(App\Models\Category::latest()->take(5)->get() as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if($category->parent_category == 0)
                                Top Level
                            @else
                                {{ App\Models\Category::find($category->parent_category)->name }}
                            @endif
                        </td>
                        <td>{{ $category->created_at ? $category->created_at->format('d M, Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Latest contents table -->
    <div class="row mt-4">
        <div class="col">
            <h2>Latest Contents</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach(App\Models\Content::latest()->take(5)->get() as $content)
                    <tr>
                        <td>{{ $content->id }}</td>
                        <td>{{ $content->name }}</td>
                        <td>{{ $content->category->name }}</td>
                        <td>{{ $content->status ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $content->is_featured ? 'Yes' : 'No' }}</td>
                        <td>{{ $content->created_at->format('d M, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
