@extends('layouts.portal')

@section('content')
<div class="container">
    <h1>Edit Categories for {{ $portal->name }}</h1>

    <form action="{{ route('portals.updateCategories', $portal->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            @foreach($categories->where('parent_category', 0) as $parentCategory)
                <h3>
                    <input class="form-check-input" type="checkbox" name="categories[]"
                           value="{{ $parentCategory->id }}" id="category{{ $parentCategory->id }}"
                           {{ in_array($parentCategory->id, $selectedCategories) ? 'checked' : '' }}>
                    <label class="font-weight-bold">{{ $parentCategory->id }} :  {{ $parentCategory->name }}</label>
                </h3>
                @foreach($categories->where('parent_category', $parentCategory->id) as $subCategory)
                    <div class="form-check" style="margin-left: 40px;"> <!-- Adjust the margin as needed -->
                        <input class="form-check-input" type="checkbox" name="categories[]"
                               value="{{ $subCategory->id }}" id="category{{ $subCategory->id }}"
                               {{ in_array($subCategory->id, $selectedCategories) ? 'checked' : '' }}>
                        <label class="form-check-label" for="category{{ $subCategory->id }}">
                            {{ $subCategory->id }} : {{ $subCategory->name }}
                        </label>
                    </div>
                @endforeach
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update Categories</button>
    </form>
</div>
@endsection
