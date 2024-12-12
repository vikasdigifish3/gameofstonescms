<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS</title>
    <link rel="icon" type="image/x-icon" href="{{asset("images/logo-1.png")}}">
    <script src="https://cdn.tiny.cloud/1/u3xy1esk19bnpd1jpkqkx1itctb5cpixzsl2gqk2nsbn3rim/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css">
    {{--    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script></head>--}}
</head>
<body>


<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('dashboard')}}">
                <img src="{{asset("images/logo-1.png")}}" alt="Logo" style="width: 65px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="container">
                <div class="row">

                    <div class="col-md-2">
                        <div class="list-group">
                            <a href="{{ route('portals.index') }}" class="list-group-item list-group-item-action">Portals</a>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="list-group">
                            <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action">Categories</a>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="list-group">
                            <a href="{{ route('contents.index') }}" class="list-group-item list-group-item-action">Contents</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="list-group">
                            <a href="{{ route('countries.index') }}" class="list-group-item list-group-item-action">Countries</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="list-group">
                            <a href="{{ route('languages.index') }}" class="list-group-item list-group-item-action">Language</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="list-group">
                            <a href="{{ route('notifications.create') }}" class="list-group-item list-group-item-action">Notifications</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    @yield('content')
</main>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2/dist/js/select2.min.js"></script>

<!-- End of body section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- If jQuery is not already included -->
<script src="https://cdn.jsdelivr.net/npm/select2/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tags').select2({
            placeholder: "Select Tags",
            allowClear: true
        });
    });
</script>

</body>
</html>
