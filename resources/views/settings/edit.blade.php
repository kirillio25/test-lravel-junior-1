@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Настройки Google Sheet</h1>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Google Sheet URL</label>
                <input type="url" name="google_sheet_url" class="form-control" value="{{ old('google_sheet_url', $url) }}" required>
                <div class="form-text">Пример: https://docs.google.com/spreadsheets/d/<strong>ID</strong>/edit</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Extracted Sheet ID</label>
                <input type="text" class="form-control" value="{{ $id }}" disabled>
            </div>

            <button class="btn btn-primary">Сохранить</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection
