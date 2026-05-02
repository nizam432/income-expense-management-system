@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>ক্যাটাগরি সম্পাদনা: {{ $category->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                @include('categories.form', ['category' => $category])
                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> আপডেট করুন</button>
            </form>
        </div>
    </div>
@stop