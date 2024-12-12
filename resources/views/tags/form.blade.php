<form action="{{ $action }}" method="POST">
    @csrf
    @isset($tag)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="name" class="form-label">Tag Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $tag->name ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
</form>

