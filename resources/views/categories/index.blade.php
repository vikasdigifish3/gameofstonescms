@extends('layouts.portal')

    @section('content')

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Categories </h2>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">Add New Category</a>

                    <table class="table table-bordered">

                        <tbody>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Parent</th>
                                <th>Translations</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item['category']->id }}</td>
                                    <td style="font-weight: bold">{{ $item['category']->name }}</td>
                                    <td>{{ $item['category']->status ? 'Active' : 'Inactive' }}</td>
                                    <td>Top level</td>
                                    <td><a href="{{  route('category_language.edit', $item['category']->id) }}" class="btn btn-success">Translations</a></td>
                                    <td>
                                        <a href="{{ route('categories.edit', [ 'category' => $item['category']->id]) }}" class="btn btn-success">Edit</a>
                                         <a href="{{ route('categories.allPortals', [ 'id' => $item['category']->id]) }}" class="btn btn-success">Portals</a>
                                    </td>
                                </tr>

                                @foreach ($item['subcategories'] as $subcategory)
                                    <tr>
                                        <td>{{ $subcategory->id }}</td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory->name }}</td>
                                        <td>{{ $subcategory->status ? 'Active' : 'Inactive' }}</td>
                                        <td>{{ $item['category']->name }}</td>
                                        <td><a href="{{  route('category_language.edit', $subcategory->id) }}" class="btn btn-success">Translations</a></td>
                                        <td>
                                            <a href="{{ route('categories.edit', ['category' => $subcategory->id,'return'=> url()->full()]) }}" class="btn btn-success">Edit</a>
                                            <a href="{{ route('categories.allPortals', [ 'id' => $subcategory->id]) }}" class="btn btn-success">Portals</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>

                        </tbody>
                    </table>
                    {{ $categories->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    @endsection

