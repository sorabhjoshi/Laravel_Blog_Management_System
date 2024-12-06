@extends('Blogbackend.components.layout')

@section('content')
<link rel="stylesheet" href='{{asset('css/addblogcat.css')}}'>
<div class="form-container">
    <h2>Add Blog Category</h2>
    
    <form action="/AddBlogCat" method="post" enctype="multipart/form-data">
        @csrf
        <label for="categorytitle">Category Title:</label>
        <input type="text" id="categorytitle" name="categorytitle" >
        @error('categorytitle')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

        <label for="seotitle">Seo Title:</label>
        <input type="text" id="seotitle" name="seotitle" >
        @error('seotitle')
                    <div class="text-danger">{{ $message }}</div>
        @enderror

        <label for="metakeywords">Meta Keywords:</label>
        <input type="text" id="metakeywords" name="metakeywords" >
        @error('metakeywords')
                    <div class="text-danger">{{ $message }}</div>
        @enderror

        <label for="metadescription">Meta Description:</label>
        <textarea id="metadescription" name="metadescription"></textarea>
        @error('content')
                    <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="form-group full-width">
            <button type="submit">Add Category</button>
        </div>
    </form>
</div>
@endsection
