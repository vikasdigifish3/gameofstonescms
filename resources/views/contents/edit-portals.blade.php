@extends('layouts.portal')


@section('content')
    <h1> {{ $content->name }}</h1>

    <form action="{{ route('contents.updatePortals', $content->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="portals">Portals</label>
            @foreach($portals as $portal)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="portals[]" value="{{ $portal->id }}"
                        {{ in_array($portal->id, $attachedPortals) ? 'checked' : '' }}>
                    <label class="form-check-label" for="portal{{ $portal->id }}">
                        {{ $portal->name }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update Portals</button>
    </form>

    
@endsection
