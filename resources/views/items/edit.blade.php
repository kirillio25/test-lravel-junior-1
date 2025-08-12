@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать {{ $item->title }}</h1>
        <form action="{{ route('items.update', $item) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Allowed" {{ $item->status == 'Allowed' ? 'selected' : '' }}>Allowed</option>
                    <option value="Prohibited" {{ $item->status == 'Prohibited' ? 'selected' : '' }}>Prohibited</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection
