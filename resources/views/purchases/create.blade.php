@extends('adminlte::page')

@section('title', 'Registrar Compra')

@section('content_header')
    <h1>{{ __('Registrar Nueva Compra') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- Campo de Producto (Restaurado a un select simple) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_id">{{ __('Producto') }}</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                <option value="">{{ __('Selecciona un producto') }}</option>
                                {{-- Asegúrate de que $products se pasa desde el controlador --}}
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    {{-- Campo de Proveedor --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id">{{ __('Proveedor') }}</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required>
                                {{-- Options will be populated by JavaScript --}}
                                <option value="">{{ __('Selecciona un proveedor') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Campo de Cantidad --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quantity">{{ __('Cantidad') }}</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                        </div>
                    </div>

                    {{-- Campo de Costo Unitario --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unit_cost">{{ __('Costo Unitario') }}</label>
                            <input type="number" name="unit_cost" id="unit_cost" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    {{-- Campo de Impuesto (IVA) --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tax_rate">{{ __('Tasa de Impuesto (%)') }}</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="form-control" step="0.01" min="0" value="16" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Campo de Subtotal --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subtotal_display">{{ __('Subtotal') }}</label>
                            <input type="text" id="subtotal_display" class="form-control" readonly>
                        </div>
                    </div>

                    {{-- Campo de Total --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_cost_display">{{ __('Costo Total') }}</label>
                            <input type="text" id="total_cost_display" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">{{ __('Registrar Compra') }}</button>
            </form>
        </div>
    </div>
@stop

@push('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var unitCostInput = $('#unit_cost');
        var quantityInput = $('#quantity');
        var taxRateInput = $('#tax_rate');
        var subtotalDisplay = $('#subtotal_display');
        var totalCostDisplay = $('#total_cost_display');
        var supplierSelect = $('#supplier_id');

        function updateCalculations() {
            var unitCost = parseFloat(unitCostInput.val()) || 0;
            var quantity = parseInt(quantityInput.val()) || 0;
            var taxRate = parseFloat(taxRateInput.val()) || 0;

            var subtotal = unitCost * quantity;
            var taxAmount = subtotal * (taxRate / 100);
            var totalCost = subtotal + taxAmount;

            subtotalDisplay.val(subtotal.toFixed(2));
            totalCostDisplay.val(totalCost.toFixed(2));
        }

        // Event listener para cuando se selecciona un producto
        $('#product_id').on('change', function() {
            var productId = $(this).val();

            if (productId) {
                var url = '{{ route("purchases.get-product-data", ":productId") }}';
                url = url.replace(':productId', productId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        supplierSelect.empty();
                        supplierSelect.append('<option value="">{{ __("Selecciona un proveedor") }}</option>');

                        if (response.suppliers && response.suppliers.length > 0) {
                            $.each(response.suppliers, function(key, supplier) {
                                var option = '<option value="' + supplier.id + '">' + supplier.name + ' ($' + parseFloat(supplier.price).toFixed(2) + ')</option>';
                                supplierSelect.append(option);
                            });

                            supplierSelect.val(response.lowest_price_supplier_id);
                            unitCostInput.val(response.lowest_price);
                        } else {
                            unitCostInput.val('');
                        }
                        
                        quantityInput.val(1);
                        updateCalculations();
                    },
                    error: function(xhr) {
                        console.error('Error al obtener datos del producto:', xhr);
                        supplierSelect.empty().append('<option value="">{{ __("Selecciona un proveedor") }}</option>');
                        unitCostInput.val('');
                        quantityInput.val('');
                        updateCalculations();
                    }
                });
            } else {
                supplierSelect.empty().append('<option value="">{{ __("Selecciona un producto") }}</option>');
                unitCostInput.val('');
                quantityInput.val('');
                updateCalculations();
            }
        });

        // Event listener para el cambio de proveedor
        supplierSelect.on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var priceMatch = selectedOption.text().match(/\$([\d.]+)/);
            var price = priceMatch ? parseFloat(priceMatch[1]) : 0;

            unitCostInput.val(price);
            updateCalculations();
        });

        // Event listeners para los inputs de cálculo
        unitCostInput.on('input', updateCalculations);
        quantityInput.on('input', updateCalculations);
        taxRateInput.on('input', updateCalculations);
    });
</script>
@endpush