@extends('adminlte::page')

@section('title', 'Historial de Salidas')

@section('content_header')
    <h1>Historial de Salidas de Inventario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Razón</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salidas as $movimiento)
                        <tr>
                            <td>{{ $movimiento->id }}</td>
                            <td>{{ $movimiento->product->name }}</td>
                            <td>{{ $movimiento->quantity }}</td>
                            <td>{{ $movimiento->reason }}</td>
                            <td>{{ $movimiento->user->name ?? 'N/A' }}</td>
                            <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
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