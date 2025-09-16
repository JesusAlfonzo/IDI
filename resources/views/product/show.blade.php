@extends('adminlte::page')

@section('title', $product->name)

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="mb-0">
            {{ __('Detalles del Producto') }}
        </h1>
        <div class="float-right">
            <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}">
                {{ __('Volver a la Lista') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>{{ __('Nombre:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->name }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('SKU:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->sku }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Descripción:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->description }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>{{ __('Precio:') }}</strong>
                        <p class="form-control-plaintext">{{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Stock Actual:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->currentStock() }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Umbral de Alerta:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->stock_alert_threshold }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop