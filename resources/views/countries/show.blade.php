@extends('layouts.portal')

@section('content')<!DOCTYPE html>
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

        .category {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .content-item {
            margin-left: 20px;
        }
        .check-all-btn {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h1>{{ $country->name }}</h1>
    
     <h2> Blocked Contents </h2>
    <ul>

        @foreach ($country->contents as $content)
            <li>
                {{ $content->name }}
                <a href="{{ route('contents.countries.edit', $content->id) }}">Manage Countries</a>
            </li>
        @endforeach
    </ul>
    

    <div class="content-container">
        <h2>Contents:</h2>
        <form action="{{ route('countries.contents.attachMultiple', $country->id) }}" method="POST">
            @csrf
            <hr>
            @foreach ($contentsByCategory as $category => $contents)
                <div class="category">
                    {{ $category }}
                    <button class="check-all-btn" type="button" onclick="checkAll('{{ $category }}')">Check All</button>
                    <button class="check-all-btn" type="button" onclick="uncheckAll('{{ $category }}')">Uncheck All</button>
                </div>
                @foreach ($contents as $content)
                    <div class="content-item">
                        <label>
                            <input type="checkbox" name="content_ids[]" value="{{ $content->id }}" data-category="{{ $category }}"  {{ in_array($content->id, $attachedContents) ? 'checked' : '' }}>
                            {{ $content->name }}
                        </label>
                    </div>
                @endforeach
                <hr>
            @endforeach
            <button type="submit">Add</button>
        </form>
    </div>

    <script>
        function checkAll(category) {
            const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-category="${category}"]`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function uncheckAll(category) {
            const checkboxes = document.querySelectorAll(`input[type="checkbox"][data-category="${category}"]`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    </script>
</body>
</html>
@endsection