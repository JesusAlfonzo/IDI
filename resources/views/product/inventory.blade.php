@extends('adminlte::page')

@section('title', 'Inventario de Productos')

@section('content_header')
    <h1>Inventario de Productos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Producto</th>
                        <th>SKU</th>
                        <th>Stock Actual</th>
                        <th>Umbral de Alerta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku ?? 'N/A' }}</td>
                            <td>{{ $product->currentStock() }}</td>
                            <td>{{ $product->stock_alert_threshold }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    {{-- Scripts opcionales --}}
@stop
