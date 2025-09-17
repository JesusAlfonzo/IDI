@extends('adminlte::page')

@section('title', 'Lista de Categorías')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ __('Categorías') }}</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">{{ __('Crear Nueva Categoría') }}</a>
    </div>
@stop

@section('content')
    {{-- Condición para expandir o colapsar la tarjeta --}}
    @php
        $isCollapsed = !request()->has('name');
    @endphp

    {{-- Tarjeta de Filtros --}}
    <div class="card {{ $isCollapsed ? 'collapsed-card' : '' }}">
        <div class="card-header">
            <h3 class="card-title">{{ __('Filtros de Búsqueda') }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas {{ $isCollapsed ? 'fa-plus' : 'fa-minus' }}"></i>
                </button>
            </div>
        </div>
        <div class="card-body" {{ $isCollapsed ? 'style=display:none;' : '' }}>
            <form action="{{ route('categories.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">{{ __('Nombre de Categoría') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">{{ __('Filtrar') }}</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('Limpiar Filtros') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ... (resto de tu tabla de categorías) ... --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('No') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $category->name }}</td>
                                <td style="width: 150px;">
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        <a class="btn btn-sm btn-info" href="{{ route('categories.show', $category->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar esta categoría?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('No se encontraron categorías.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $categories->links() !!}
        </div>
    </div>
@stop