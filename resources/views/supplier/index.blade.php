@extends('adminlte::page')

@section('title', 'Lista de Proveedores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ __('Proveedores') }}</h1>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">{{ __('Crear Nuevo Proveedor') }}</a>
    </div>
@stop

@section('content')
    {{-- Condición para expandir o colapsar la tarjeta --}}
    @php
        $isCollapsed = !request()->hasAny(['name', 'address', 'phone']);
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
            <form action="{{ route('suppliers.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address">{{ __('Dirección') }}</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ request('address') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">{{ __('Teléfono') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ request('phone') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">{{ __('Filtrar') }}</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">{{ __('Limpiar Filtros') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ... (resto de tu tabla de proveedores) ... --}}
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
                            <th>{{ __('Dirección') }}</th>
                            <th>{{ __('Teléfono') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td style="width: 150px;">
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                        <a class="btn btn-sm btn-info" href="{{ route('suppliers.show', $supplier->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success" href="{{ route('suppliers.edit', $supplier->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar este proveedor?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No se encontraron proveedores.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $suppliers->links() !!}
        </div>
    </div>
@stop