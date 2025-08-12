@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Все элементы</h1>

            <a href="{{ route('settings.edit') }}" class="btn btn-outline-secondary" title="Settings">Настройка</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('items.create') }}" class="btn btn-primary">Добавить</a>

            <form action="{{ route('items.generate') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-success">Сгенерировать 1000</button>
            </form>

            <form action="{{ route('items.clear') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Очистить полностью?')">Очистить</button>
            </form>

            <a href="{{ url('/fetch') }}" class="btn btn-info">Fetch (20)</a>
            <a href="{{ url('/fetch/50') }}" class="btn btn-info">Fetch (50)</a>
            <a href="{{ url('/fetch/100') }}" class="btn btn-info">Fetch (100)</a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ Str::limit($item->description, 60) }}</td>
                            <td>
                                <span class="badge
                                    @if($item->status === 'active') bg-success
                                    @elseif($item->status === 'pending') bg-warning
                                    @elseif($item->status === 'inactive') bg-secondary
                                    @else bg-info
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Нетью данных.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>

    </div>
@endsection
