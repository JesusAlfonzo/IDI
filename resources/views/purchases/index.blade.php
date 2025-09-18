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
    @push('css')
        <style>
            .select2-container .select2-selection {
                border: 1px solid #ced4da !important;
                border-radius: .25rem !important;
                min-height: calc(2.25rem + 2px);
            }
            
            .select2-container .select2-selection--single {
                height: calc(2.25rem + 1px) !important;
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 2.25rem;
                text-align: left;
            }

            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                line-height: 2.25rem;
                color: #999;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: calc(2.25rem + 2px) !important;
                top: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 35px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin-top: 1px;
            }

            /* Estilos específicos para el select multiple */
            .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
                display: flex;
                flex-wrap: wrap;
                padding-top: .25rem;
                padding-left: .25rem;
            }

            .select2-container .select2-dropdown {
                max-height: 200px;
                overflow-y: auto;
            }
        </style>
    @endpush

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
                            <select name="product_id[]" id="product_id" class="form-control" multiple style="width: 100%;">
                                @if(request('product_id'))
                                    @foreach($selectedProducts as $product)
                                        <option value="{{ $product->id }}" selected>
                                            {{ $product->name }} ({{ $product->sku }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Filtro de Proveedor --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="supplier_id">{{ __('Proveedor') }}</label>
                            <select name="supplier_id[]" id="supplier_id" class="form-control" multiple style="width: 100%;">
                                @if(request('supplier_id'))
                                    @foreach($selectedSuppliers as $supplier)
                                        <option value="{{ $supplier->id }}" selected>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Filtros de Fecha --}}
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

@push('js')
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para el filtro de productos
            $('#product_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Escribe para buscar un producto',
                minimumInputLength: 2,
                allowClear: true,
                multiple: true,
                ajax: {
                    url: '{{ route("purchases.search-products") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });

            // Inicializar Select2 para el filtro de proveedores
            $('#supplier_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Escribe para buscar un proveedor',
                minimumInputLength: 2,
                allowClear: true,
                multiple: true,
                ajax: {
                    url: '{{ route("purchases.search-suppliers") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
