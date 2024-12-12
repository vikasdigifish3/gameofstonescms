@extends('layouts.portal')



@section('content')
    <div class="container">
        <h1>Edit Portal - Content Relationship</h1>

        <form action="{{ route('portals.updateContents', $portal->id) }}" method="POST">
            @csrf
             @method('PUT')
            <hr>
            @foreach ($contentsByCategory as $category => $contents)
                <div class="category">
                    <h3>{{ $category }}</h3>
                    <button class="check-all-btn" type="button" onclick="checkAll('{{ $category }}')">Check All</button>
                    <button class="check-all-btn" type="button" onclick="uncheckAll('{{ $category }}')">Uncheck All</button>
                </div>
                @foreach ($contents as $content)
                    <div class="content-item">
                        <label>
                            <input type="checkbox" name="content_ids[]" value="{{ $content->id }}" data-category="{{ $category }}"  {{ in_array($content->id, $attachedContents) ? 'checked' : '' }}>
                            {{ $content->id }} :   &nbsp; {{ $content->name }}
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
<!--
        <form action="{{ route('portals.multiAttachContents', $portal->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="attach-contents">Attach Contents</label>
                <div class="checkbox-group">
                    @foreach($contents as $content)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="contents[]" id="attach-content{{ $content->id }}" value="{{ $content->id }}">
                            <label class="form-check-label" for="attach-content{{ $content->id }}">
                                {{ $content->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-success">Attach Contents</button>
        </form>

        <form action="{{ route('portals.multiDetachContents', $portal->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="detach-contents">Detach Contents</label>
                <div class="checkbox-group">
                    @foreach($portal->contents as $content)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="contents[]" id="detach-content{{ $content->id }}" value="{{ $content->id }}">
                            <label class="form-check-label" for="detach-content{{ $content->id }}">
                                {{ $content->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-danger">Detach Contents</button>
        </form>
    -->
    </div>
@endsection
