@extends('layouts.portal')

@section('content')
    <h2>{{$category->name}} </h2>

<form action="{{ route('categories.updatePortals', $category->id) }}" method="POST">
    @csrf
    @method('PUT')



    <div class="form-group">
        <label for="portals">Portals</label>
        @foreach($portals as $portal)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="portals[]" value="{{ $portal->id }}"
                    {{ in_array($portal->id, $selectedPortals) ? 'checked' : '' }}>
                <label class="form-check-label" for="portal{{ $portal->id }}">
                    {{ $portal->name }}
                </label>
            </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
