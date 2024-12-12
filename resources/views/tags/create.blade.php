@extends('layouts.portal')

@section('content')
    <div class="container">
        <h2>Create Tag</h2>
        @include('tags.form', ['action' => route('tags.store'), 'buttonText' => 'Create'])
    </div>
@endsection
