@extends('adminlte::page')

@section('title', 'Lista de Categorías')

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="mb-0">
            {{ __('Categorías') }}
        </h1>
        <div class="float-right">
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                {{ __('Crear Nueva Categoría') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead">
                        <tr>
                            <th>No</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Descripción') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description }}</td>
                                <td style="width: 150px;">
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('categories.show', $category->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar esta categoría?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $categories->links() !!}
        </div>
    </div>
@stop