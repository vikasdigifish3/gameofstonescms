@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Add Language</h2>
                <form action="{{ route('languages.store')}}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="shortcode">Shortcode</label>
                        <input type="text" class="form-control" id="shortcode" name="shortcode" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="0">Inactive</option>
                            <option value="1" selected>Active</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Language</button>
                </form>
            </div>
        </div>
    </div>
@endsection
