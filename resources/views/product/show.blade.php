@extends('adminlte::page')

@section('title', 'Detalles de ' . $product->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">
            {{ __('Detalles del Producto') }}
        </h1>
        <div class="float-right">
            <a class="btn btn-secondary btn-sm" href="{{ route('products.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('Volver a la Lista') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Información del Producto') }}</h3>
            <div class="card-tools">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
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
                        <strong>{{ __('Categoría:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->category->name ?? 'Sin Categoría' }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Descripción:') }}</strong>
                        <p class="form-control-plaintext">{{ $product->description }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>{{ __('Precio de Venta:') }}</strong>
                        <p class="form-control-plaintext">${{ number_format($product->price, 2) }}</p>
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
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('products.show-purchase-prices', $product->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-money-bill-alt"></i> {{ __('Gestionar Precios de Compra') }}
            </a>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-success">
                <i class="fas fa-edit"></i> {{ __('Editar Producto') }}
            </a>
        </div>
    </div>
@stop