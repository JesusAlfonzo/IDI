@extends('adminlte::page')

@section('title', 'Registrar Salida de Inventario')

@section('content_header')
    <h1>Registrar Salida de Inventario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('inventory.store_out') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="product_id">Producto</label>
                    <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                        <option value="">Selecciona un producto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">Cantidad a dar de baja</label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required min="1">
                    @error('quantity')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reason">Razón de la Salida</label>
                    <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger">Registrar Salida</button>
            </form>
        </div>
    </div>
@stop

@section('js')
    {{-- Scripts opcionales --}}
@stop