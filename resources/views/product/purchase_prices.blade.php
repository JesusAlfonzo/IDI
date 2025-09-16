@extends('adminlte::page')

@section('title', 'Precios de Compra para ' . $product->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ __('Precios de Compra de') }} {{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">{{ __('Volver a Productos') }}</a>
    </div>
@stop

@section('content')
    <div class="row">
        {{-- Formulario para añadir nuevo precio --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Añadir Nuevo Precio') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.add-purchase-price', $product->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="supplier_id">{{ __('Proveedor') }}</label>
                            <select name="supplier_id" id="supplier_id_form" class="form-control @error('supplier_id') is-invalid @enderror">
                                <option value="">{{ __('Selecciona un proveedor') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="purchase_price">{{ __('Precio de Compra') }}</label>
                            <input type="number" name="purchase_price" id="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" step="0.01" min="0" value="{{ old('purchase_price') }}">
                            @error('purchase_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('Añadir Precio') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabla de precios existentes con filtro --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Precios Existentes') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="filter_supplier_id">{{ __('Filtrar por Proveedor') }}</label>
                        <select id="filter_supplier_id" class="form-control">
                            <option value="">{{ __('Todos los Proveedores') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Proveedor') }}</th>
                                    <th>{{ __('Precio') }}</th>
                                </tr>
                            </thead>
                            <tbody id="purchase_prices_table_body">
                                @forelse ($product->purchasePrices as $purchasePrice)
                                    <tr>
                                        <td>{{ $purchasePrice->supplier->name }}</td>
                                        <td>${{ number_format($purchasePrice->purchase_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">{{ __('No hay precios de compra registrados.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#filter_supplier_id').on('change', function() {
            var supplierId = $(this).val();
            var productId = '{{ $product->id }}';
            var url = '{{ route("products.filter-purchase-prices", ["id" => ":productId"]) }}';
            url = url.replace(':productId', productId);

            $.ajax({
                url: url,
                type: 'GET',
                data: { supplier_id: supplierId },
                success: function(response) {
                    var tableBody = $('#purchase_prices_table_body');
                    tableBody.empty(); // Limpiar la tabla

                    if (response.data.length > 0) {
                        $.each(response.data, function(key, purchasePrice) {
                            var row = '<tr>' +
                                '<td>' + purchasePrice.supplier.name + '</td>' +
                                '<td>$' + parseFloat(purchasePrice.purchase_price).toFixed(2) + '</td>' +
                                '</tr>';
                            tableBody.append(row);
                        });
                    } else {
                        var emptyRow = '<tr><td colspan="2" class="text-center">{{ __("No se encontraron precios para este proveedor.") }}</td></tr>';
                        tableBody.append(emptyRow);
                    }
                },
                error: function(error) {
                    console.error('Error al filtrar los precios:', error);
                }
            });
        });
    });
</script>
@endpush