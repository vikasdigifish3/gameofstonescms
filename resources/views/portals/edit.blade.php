@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Portal</h2>
                <form action="{{ route('portals.update', $portal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $portal->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled>Select the status</option>
                            <option value="0" {{ $portal->status == 0 ? 'selected' : '' }}>Inactive</option>
                            <option value="1" {{ $portal->status == 1 ? 'selected' : '' }}>Active</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Portal</button>
                </form>
            </div>
        </div>
    </div>
@endsection
