@extends('layouts.portal')

@section('content')<!-- contents/countries/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <!-- CSS styles and other HTML head elements -->
</head>
<body>
    
    <h1>Content: {{ $content->name }}</h1>
    <h3>Block COuntries here </h3>
    <form action="{{ route('contents.countries.update', $content->id) }}" method="POST">
        @csrf
        <h3>Blocked Countries:</h3>
        <ul>
            @foreach ($attachedCountries as $countryId)
                <li>{{ $countries->find($countryId)->name }}</li>
            @endforeach
        </ul>

        
        @foreach ($continents as $continent)
        <H1>
            {{$continent->name}}
        </h1>
            @foreach ($continent->countries as $country)
                <label>
                    <input type="checkbox" name="country_ids[]" value="{{ $country->id }}" {{ in_array($country->id, $attachedCountries) ? 'checked' : '' }}>
                    {{ $country->name }}
                </label><br>
            @endforeach
        @endforeach

        <button type="submit">Block Countries</button>
    </form>
</body>
</html>
@endsection

