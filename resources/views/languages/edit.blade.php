@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Language</h2>
                <form action="{{ route('languages.update', [ 'language' => $language->id]) }}" method="POST">
    @csrf
    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $language->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="shortcode">Shortcode</label>
                        <input type="text" class="form-control" id="shortcode" name="shortcode" value="{{ $language->shortcode }}" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled>Select the status</option>
                            <option value="0" {{ $language->status == 0 ? 'selected' : '' }}>Inactive</option>
                            <option value="1" {{ $language->status == 1 ? 'selected' : '' }}>Active</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Language</button>
                </form>
            </div>
        </div>
    </div>
@endsection

