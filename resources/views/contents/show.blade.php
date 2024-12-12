@extends('layouts.portal')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 <a href="{{ route('contents.create',) }}" class="btn btn-primary">Add New Content</a>
                 <br/><br/><br/>
                <h2>Content Details: </h2>
                
                    <div class="form-group">
                        <b>Name </b>  : {{ $content->name }} 
                        <br/><br/><br/>
                        <b>Description </b>  : {{ $content->description }} 
                        
                    </div>

            </div>
        </div>
    </div>
    
@endsection



