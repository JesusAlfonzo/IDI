@extends('adminlte::page')

@section('title', 'Historial de Compras')

{{-- Incluye los CSS y JS de Select2 --}}
@section('plugins.Select2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ __('Historial de Compras') }}</h1>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">{{ __('Registrar Nueva Compra') }}</a>
    </div>
@stop

@section('content')
    {{-- CSS para corregir la altura, el centrado vertical y la alineación a la izquierda del texto y placeholders de Select2 --}}
    <style>
        /* Asegura que el campo select2 tenga la misma altura que otros inputs de Bootstrap */
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
            border: 1px solid #ced4da !important;
            display: flex; /* Usamos Flexbox para mejor control */
            align-items: center; /* Centra verticalmente el contenido principal */
        }

        /* Alinea el contenido renderizado (cuando algo está seleccionado) a la izquierda y centrado verticalmente */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.25rem;  
            text-align: left; 
        }
        
        /* Centra verticalmente el placeholder */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            line-height: 2.25rem; /* Iguala la altura de línea del contenedor */
            color: #999; /* Color del placeholder */
        }

        /* Ajusta la posición y tamaño del icono de flecha */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px) !important;
            top: 0; /* Lo alineamos desde la parte superior */
            display: flex;
            align-items: center; /* Centra verticalmente la flecha */
            justify-content: center; /* Centra horizontalmente la flecha */
            width: 35px; /* Ancho fijo para el contenedor de la flecha */
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            /* Estilos para la flecha en sí */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centra la flecha con precisión */
            margin-top: 1px; /* Ajuste fino si es necesario */
        }
    </style>
    
    @php
        $isCollapsed = !request()->hasAny(['product_id', 'supplier_id', 'start_date', 'end_date']);
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
            <form action="{{ route('purchases.index') }}" method="GET">
                <div class="row">
                    {{-- Filtro de Producto --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id">{{ __('Producto') }}</label>
                            <select name="product_id" id="product_id" class="form-control" style="width: 100%;">
                                <option value="">{{ __('Todos') }}</option>
                                @if(isset($selectedProduct) && $selectedProduct)
                                    <option value="{{ $selectedProduct->id }}" selected>
                                        {{ $selectedProduct->name }} ({{ $selectedProduct->sku }})
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    {{-- Filtro de Proveedor --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">{{ __('Proveedor') }}</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" style="width: 100%;">
                                <option value="">{{ __('Todos') }}</option>
                                @if(isset($selectedSupplier) && $selectedSupplier)
                                    <option value="{{ $selectedSupplier->id }}" selected>
                                        {{ $selectedSupplier->name }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">{{ __('Fecha Inicio') }}</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">{{ __('Fecha Fin') }}</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">{{ __('Filtrar') }}</button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">{{ __('Limpiar Filtros') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ... (resto de la vista, tabla y paginación) ... --}}
    
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
                            <th>{{ __('Fecha') }}</th>
                            <th>{{ __('Producto') }}</th>
                            <th>{{ __('Proveedor') }}</th>
                            <th>{{ __('Cantidad') }}</th>
                            <th>{{ __('Costo Unitario') }}</th>
                            <th>{{ __('Subtotal') }}</th>
                            <th>{{ __('Impuesto') }}</th>
                            <th>{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                                <td>{{ $purchase->product->name ?? 'N/A' }}</td>
                                <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>${{ number_format($purchase->unit_cost, 2) }}</td>
                                <td>${{ number_format($purchase->subtotal, 2) }}</td>
                                <td>{{ number_format($purchase->tax_rate, 2) }}% (${{ number_format($purchase->tax_amount, 2) }})</td>
                                <td>${{ number_format($purchase->total_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">{{ __('No se encontraron registros de compras.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $purchases->links() !!}
        </div>
    </div>
@stop

{{-- @push('js')
<script>
    $(document).ready(function() {
        // Inicializar Select2 para el filtro de productos
        $('#product_id').select2({
            placeholder: 'Escribe para buscar un producto',
            minimumInputLength: 2,
            allowClear: true,
                ajax: {
                    // *** ¡CORRECCIÓN AQUÍ! Usando la ruta correcta para buscar proveedores ***
                    url: '{{ route("purchases.search-suppliers") }}', 
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
        });
    }

// Inicializar Select2 para el filtro de proveedores
$('#supplier_id').select2({
    placeholder: 'Escribe para buscar un proveedor',
    minimumInputLength: 2,
    allowClear: true,
    ajax: {
        // *** CORRECTED ROUTE NAME HERE ***
        url: '{{ route("purchases.search-suppliers") }}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term
            };
        },
        processResults: function (data) {
            return {
                results: data.results
            };
        },
        cache: true
    }
});
</script>
@endpush --}}