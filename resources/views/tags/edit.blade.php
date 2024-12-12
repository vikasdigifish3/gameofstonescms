@extends('layouts.portal')

@section('content')
    <div class="container">
        <h2>Edit Tag</h2>
        @include('tags.form', ['action' => route('tags.update', $tag), 'buttonText' => 'Update', 'tag' => $tag])
    </div>
@endsection
