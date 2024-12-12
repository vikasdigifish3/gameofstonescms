@extends('layouts.portal')

@section('content')
    <!-- Styles and content omitted for brevity -->

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Create Category</h2>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="0">Inactive</option>
                            <option value="1" selected>Active</option>
                        </select>
                    </div>
                    

                    <div class="form-group">
                        <label for="status">Parent Category</label>
                        <select class="form-select" id="parent_category" name="parent_category" required>
                            <option value="0">Top level</option>
                            @foreach($categories as $list)
                                @if ($list->parent_category == 0)
                                    <option value="{{$list->id}}">{{$list->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </form>
            </div>
        </div>
    </div>
@endsection
