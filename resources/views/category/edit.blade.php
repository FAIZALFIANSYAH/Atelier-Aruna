@extends('layouts.app')

@section('content')
<!-- <h1>Edit form Category</h1> -->



<div class="card mb-3">

    <div class="card-header">
        Edit form Category
    </div>

    <div class="card-body">

        <form action="{{route('category.update', $category->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="input-group">


                <input type="text" name="name" value="{{old ('name', $category->name)}}">
                @error('name')
                <div style="color: red;">{{ $message }}</div>
                @enderror

                <button
                    type="submit"
                    class="btn btn-primary">

                    Update

                </button>

            </div>

        </form>

    </div>

</div>


@endsection