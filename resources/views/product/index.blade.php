@extends('adminlte::page')

@section('title', 'Lista de Productos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ __('Productos') }}</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">{{ __('Crear Nuevo Producto') }}</a>
    </div>
@stop

@section('content')
    @php
        $isCollapsed = !request()->hasAny(['name', 'sku', 'category_id', 'supplier_id']);
    @endphp

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
                    {{-- Filtro de Nombre con Select2 --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">{{ __('Nombre') }}</label>
                            <select name="name[]" id="name" class="form-control select2" multiple style="width: 100%;">
                                @foreach ($products as $product)
                                    <option value="{{ $product->name }}" {{ in_array($product->name, (array) request('name')) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Filtro de SKU con Select2 --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sku">{{ __('SKU') }}</label>
                            <select name="sku[]" id="sku" class="form-control select2" multiple style="width: 100%;">
                                @foreach ($products as $product)
                                    <option value="{{ $product->sku }}" {{ in_array($product->sku, (array) request('sku')) ? 'selected' : '' }}>
                                        {{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Filtro de Categoría con Select2 --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">{{ __('Categoría') }}</label>
                            <select name="category_id[]" id="category_id" class="form-control select2" multiple style="width: 100%;">
                                <option value="">{{ __('Todas') }}</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, (array) request('category_id')) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Filtro de Proveedor con Select2 --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">{{ __('Proveedor') }}</label>
                            <select name="supplier_id[]" id="supplier_id" class="form-control select2" multiple style="width: 100%;">
                                <option value="">{{ __('Todos') }}</option>
                                @foreach($suppliers as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, (array) request('supplier_id')) ? 'selected' : '' }}>{{ $name }}</option>
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

@push('css')
    {{-- Estilos de Select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    {{-- Estilos para la integración con Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.2.0/dist/select2-bootstrap4-theme.min.css">
    {{-- CSS personalizado para corregir el ancho y la lista --}}
    <style>
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
        }
        .select2-container--bootstrap4 .select2-selection {
            border: 1px solid #ced4da;
            padding: .1rem .75rem;
            line-height: 1.5;
            border-radius: .25rem;
            height: auto !important;
        }
        .select2-container .select2-dropdown {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                closeOnSelect: false,
                maximumSelectionLength: 10,
            });
        });
    </script>
@endpush