@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Principal</h1>
@stop

@section('content')
    <div class="row">
        {{-- Card de Productos --}}
        <div class="col-md-3">
            <x-adminlte-small-box title="{{ $totalProducts }}" text="Total de Productos" icon="fas fa-box" theme="info"/>
        </div>

        {{-- Card de Proveedores --}}
        <div class="col-md-3">
            <x-adminlte-small-box title="{{ $totalSuppliers }}" text="Total de Proveedores" icon="fas fa-truck" theme="success"/>
        </div>

        {{-- Card de Compras --}}
        <div class="col-md-3">
            <x-adminlte-small-box title="${{ number_format($totalPurchases, 2) }}" text="Valor Total de Compras" icon="fas fa-shopping-cart" theme="warning"/>
        </div>

        {{-- Card de Usuarios --}}
        <div class="col-md-3">
            <x-adminlte-small-box title="{{ $totalUsers }}" text="Usuarios Registrados" icon="fas fa-users" theme="secondary"/>
        </div>
    </div>

    {{-- Alertas de Stock Bajo --}}
    @if($lowStockProducts->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">🚨 Alerta: Productos con Bajo Stock</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Stock Actual</th>
                                <th>Umbral de Alerta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td><span class="badge badge-danger">{{ $product->currentStock() }}</span></td>
                                    <td>{{ $product->stock_alert_threshold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Actividad Reciente --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Últimos Movimientos de Inventario</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Razón</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                                <tr>
                                    <td>{{ $movement->product->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $movement->type == 'entrada' ? 'success' : 'danger' }}">
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->reason }}</td>
                                    <td>{{ $movement->user->name ?? 'N/A' }}</td>
                                    <td>{{ $movement->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {{-- Scripts opcionales --}}
@stop