@extends('adminlte::page')

@section('title', 'Lista de Productos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ __('Productos') }}</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">{{ __('Crear Nuevo Producto') }}</a>
    </div>
@stop

@section('content')
    {{-- Condición para expandir o colapsar la tarjeta --}}
    @php
        $isCollapsed = !request()->hasAny(['name', 'sku', 'category_id', 'supplier_id']);
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
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sku">{{ __('SKU') }}</label>
                            <input type="text" name="sku" id="sku" class="form-control" value="{{ request('sku') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">{{ __('Categoría') }}</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">{{ __('Todas') }}</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">{{ __('Proveedor') }}</label>
                            <select name="supplier_id" id="supplier_id" class="form-control">
                                <option value="">{{ __('Todos') }}</option>
                                @foreach($suppliers as $id => $name)
                                    <option value="{{ $id }}" {{ request('supplier_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">{{ __('Filtrar') }}</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('Limpiar Filtros') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                            <th>{{ __('SKU') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Categoría') }}</th>
                            <th>{{ __('Proveedor Principal') }}</th>
                            <th>{{ __('Stock') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $product->currentStock() }}</td>
                                <td style="width: 150px;">
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        <a class="btn btn-sm btn-info" href="{{ route('products.show', $product->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success" href="{{ route('products.edit', $product->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar este producto?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('No se encontraron productos.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $products->links() !!}
        </div>
    </div>
@stop