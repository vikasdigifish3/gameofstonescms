@extends('layouts.portal')

@section('content')
<style>
        /* General styles */
        h2 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: black;
            font-weight: bold;
        }

        .container {
        max-width: 1140px;
        margin: 0 auto;
        padding: 1rem;
        display: flex;
        justify-content: center;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
    }

    h2 {
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    label {
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        user-select: none;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover

</style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Category</h2>
                <form action="{{ route('categories.update', [ $category->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="return" value="{{ request('return') }}">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status" aria-label="Default select example" required>
                            <option value="">Select the status</option>
                            <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                            <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                        </select>
                    </div>

{{--                    <div class="form-group">--}}
{{--                        <label for="status">Parent Category</label>--}}
{{--                        <select class="form-select" id="parent_category" name="parent_category" required>--}}
{{--                            <option value="0" {{($category->parent_category == 0 ? 'selected' : '')}}>Top level</option>--}}
{{--                            @foreach($categories as $list)--}}
{{--                                <option value="{{$list->id}}" {{($category->parent_category == $list->id ? 'selected' : '')}}>{{$list->name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}


                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary" style="color: #fff; background-color: #0d6efd; border-color: #0d6efd;">Go Back</a>
    <div class="container">
                </form>
            </div>
        </div>
    </div>
@endsection
