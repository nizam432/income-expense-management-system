@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <h1><i class="fas fa-tags"></i> ক্যাটাগরি ম্যানেজমেন্ট</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">সমস্ত ক্যাটাগরি</h3>
                    <div class="card-tools">
                        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> নতুন ক্যাটাগরি যোগ করুন
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>নাম</th>
                                <th>প্রকার</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $category->type == 'income' ? 'success' : 'danger' }}">
                                            {{ ucfirst($category->type) == 'Income' ? 'আয়' : 'ব্যয়' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-xs btn-primary mr-1"><i class="fas fa-edit"></i> এডিট</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('আপনি কি নিশ্চিত? এই ক্যাটাগরিটি ডিলিট হয়ে যাবে।');"><i class="fas fa-trash"></i> ডিলিট</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop