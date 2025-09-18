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
                            <select name="name[]" id="name" class="form-control select2" multiple
                                style="width: 100%;">
                                <option value="">Selecciona un proveedor</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->name }}"
                                        {{ in_array($supplier->name, (array) request('name')) ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address">{{ __('Dirección') }}</label>
                            {{-- Modificado para usar Select2 --}}
                            <select name="address[]" id="address" class="form-control select2" multiple
                                style="width: 100%;">
                                <option value="">Selecciona una dirección</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->address }}"
                                        {{ in_array($supplier->address, (array) request('address')) ? 'selected' : '' }}>
                                        {{ $supplier->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">{{ __('Teléfono') }}</label>
                            {{-- Modificado para usar Select2 --}}
                            <select name="phone[]" id="phone" class="form-control select2" multiple
                                style="width: 100%;">
                                <option value=""></option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->phone }}"
                                        {{ in_array($supplier->phone, (array) request('phone')) ? 'selected' : '' }}>
                                        {{ $supplier->phone }}
                                    </option>
                                @endforeach
                            </select>
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
                                        <a class="btn btn-sm btn-info"
                                            href="{{ route('suppliers.show', $supplier->id) }}"><i
                                                class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('suppliers.edit', $supplier->id) }}"><i
                                                class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar este proveedor?') ? this.closest('form').submit() : false;"><i
                                                class="fa fa-fw fa-trash"></i></button>
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

@push('css')
    {{-- Estilos de Select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    {{-- Estilos para la integración con Bootstrap 4 --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.2.0/dist/select2-bootstrap4-theme.min.css">

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
        max-height: 200px; /* Puedes ajustar esta altura */
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