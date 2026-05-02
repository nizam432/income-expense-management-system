@extends('adminlte::page')

@section('title', 'Add Category')

@section('content_header')
    <h1>নতুন ক্যাটাগরি যোগ করুন</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                @include('categories.form')
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> সেভ করুন</button>
            </form>
        </div>
    </div>
@stop