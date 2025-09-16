@extends('adminlte::page')

@section('title', 'Historial de Compras')

@section('content_header')
    <h1>Historial de Compras</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Subtotal</th>
                        <th>Impuesto (%)</th>
                        <th>Monto Impuesto</th>
                        <th>Costo Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->product->name }}</td>
                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $purchase->quantity }}</td>
                            <td>{{ number_format($purchase->unit_cost, 2) }}</td>
                            <td>{{ number_format($purchase->subtotal, 2) }}</td>
                            <td>{{ number_format($purchase->tax_rate * 100, 0) }}%</td>
                            <td>{{ number_format($purchase->tax_amount, 2) }}</td>
                            <td>{{ number_format($purchase->total_cost, 2) }}</td>
                            <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    {{-- Scripts opcionales como DataTables para la tabla --}}
@stop