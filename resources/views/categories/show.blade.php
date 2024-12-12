@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Category Details</h2>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Go Back</a>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" value="{{ $category->name }}" readonly>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" value="{{ $category->status }}" readonly>
                </div>
            </div>
        </div>
    </div>
@endsection
