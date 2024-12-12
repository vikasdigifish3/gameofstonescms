@extends('layouts.portal')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-bottom: 20px;
        }

        .content-container {
            margin-bottom: 20px;
        }

        .content-container h2 {
            margin-bottom: 10px;
        }

        .content-container ul {
            margin-bottom: 10px;
        }

        .content-container li {
            list-style-type: square;
            margin-left: 20px;
        }

        .form-container {
            width: 400px;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .form-container button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>{{ $country->name }}</h1>

    <div class="content-container">
        <h2>Contents:</h2>
        <ul>
            @foreach ($country->contents as $content)
                <li>{{ $content->title }}</li>
            @endforeach
        </ul>
    </div>

    <div class="form-container">
        <form action="{{ route('countries.contents.detach', $country->id) }}" method="POST">
            @csrf
            <div>
                <label for="content_id">Remove Content:</label>
                <select name="content_id" id="content_id">
                    @foreach ($country->contents as $content)
                        <option value="{{ $content->id }}">{{ $content->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Remove</button>
        </form>
    </div>
</body>
</html>

@endsection