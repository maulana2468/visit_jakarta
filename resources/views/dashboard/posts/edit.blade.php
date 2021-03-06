@extends('dashboard.layout.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Post</h1>
        
      </div>

      
      <div class="col-lg-8">
        <form method="POST" action="/dashboard/posts/{{ $post->slug }}" enctype="multipart/form-data">
          @method('put')
          @csrf  
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required autofocus value="{{ old('title', $post->title) }}">
            @error('title')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror   
          </div>
          <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" required value="{{ old('slug', $post->slug) }}">
            @error('slug')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror   
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" name="category_id">
            @foreach ($categories as $category)
              @if (old('category_id', $post->category_id) == $category->id)    
                  <option value="{{ $category->id }}" selected>{{ $category->name }}</option>                               
              @else
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endif
            @endforeach 
            </select>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label">Location/Culinary</label>
            <select class="form-select" name="location_id">
            @foreach ($locations as $location)
              @if (old('location_id', $post->location_id) == $location->id)
                @if ($location->name == 'Jakarta Utara')
                  <option value="{{ $location->id }}" selected>{{ $location->name }}/Makanan</option>
                @elseif ($location->name == 'Jakarta Selatan')
                  <option value="{{ $location->id }}" selected>{{ $location->name }}/Minuman</option>
                @else
                  <option value="{{ $location->id }}" selected>{{ $location->name }}</option>
                @endif                      
              @else
                @if ($location->name == 'Jakarta Utara')
                  <option value="{{ $location->id }}">{{ $location->name }}/Makanan</option>
                @elseif ($location->name == 'Jakarta Selatan')
                  <option value="{{ $location->id }}">{{ $location->name }}/Minuman</option>
                @else
                  <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endif
              @endif
            @endforeach  
            </select>
          </div>

          <div class="mb-3">
            <label for="mainPhoto" class="form-label">Main Photo</label>
            <input type="hidden" name="oldImage" value="{{ $post->mainPhoto }}">
            @if ($post->mainPhoto)
              <img src="{{ asset('storage/' . $post->mainPhoto) }}" class="img-preview img-fluid mb-3 col-sm-5 d-block">
            @else 
              <img class="img-preview img-fluid mb-3 col-sm-5">
            @endif
            <input class="form-control @error('mainPhoto') is-invalid @enderror" type="file" id="mainPhoto" name="mainPhoto" onchange="previewImage()">
            @error('mainPhoto')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror  
          </div>

          <div class="mb-3">
            <label for="images" class="form-label">Post Image</label>
            <!-- <input type="hidden" name="oldImage" value="{{ $post->image }}"> -->
            <img class="img-preview img-fluid mb-3 col-sm-5">
            <input class="form-control @error('images') is-invalid @enderror" type="file" id="images" name="images[]" multiple>
            @error('images')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror  
          </div>

          <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            @error('body')
              <p class="text-danger"> {{ $message }} </p>
            @enderror
              <input id="body" type="hidden" name="body" value="{{ old('body', $post->body) }}">
              <trix-editor input="body"></trix-editor>
          </div>
          
          <div class="my-4">
            <button type="submit" class="btn btn-primary">Update Post</button>
          </div>
          
        </form>
      </div>

<script>
  const title = document.querySelector('#title');
  const slug = document.querySelector('#slug');

  title.addEventListener('change', function() {
    fetch('/dashboard/post/checkSlug?title=' + title.value)
        .then(response => response.json())
        .then(data => slug.value = data.slug)
  });

  document.addEventListener('trix-file-accept', function(event) {
    event.preventDefault();
  });

  function previewImage() {
    const image = document.querySelector('#mainPhoto');
    const imgPreview = document.querySelector('.img-preview');

    imgPreview.style.display = "block";

    const ofReader = new FileReader();
    ofReader.readAsDataURL(image.files[0]);

    ofReader.onload = function(event) {
      imgPreview.src = event.target.result;
    };
  }
</script>
@endsection