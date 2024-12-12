@extends('layouts.portal')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Check if there is a "success" message in the session --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <h2>Send Notification</h2>
        <form action="{{ route('notifications.send') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" >
            </div>
            <div class="form-group">
                <label for="body">Body:</label>
                <textarea class="form-control" id="body" name="body" ></textarea>
            </div>
            <div class="form-group">
                <label for="icon">Icon:</label>
                <input type="file" class="form-control" id="icon" name="icon" required>
            </div>
            @if ($errors->any()) <div>{{ $errors->first('icon') }}</div> @endif
            <div class="form-group">
                <label for="subdomain">Subdomain:</label>
                <select class="form-control" id="subdomain" name="subdomain" style="text-transform: capitalize">
                    @foreach($subdomains as $subdomain)
                        <option value="{{ $subdomain }}">{{ $subdomain }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <select class="form-control" id="country" name="country">
                    <option value="all">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="userCategory">User Category:</label>
                <select class="form-control" id="userCategory" name="userCategory">
                    <option value="all">All Users</option>
                    <option value="new">New Users(Users who have registered within the last week)</option>
                    <option value="frequent">Frequent Users(Users who have visited multiple times in the last month)</option>
                    <option value="idle">Idle Users(Users who have not visited in over a month)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Send Notification</button>
        </form>
    </div>
@endsection
