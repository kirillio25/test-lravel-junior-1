@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создать</h1>
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Allowed">Allowed</option>
                    <option value="Prohibited">Prohibited</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection
