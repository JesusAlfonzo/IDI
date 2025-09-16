@extends('adminlte::page')

@section('title', 'Crear Proveedor')

@section('content_header')
    <h1>Crear Nuevo Proveedor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('suppliers.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                {{-- Aquí se incluye el formulario con los campos --}}
                @include('supplier.form')

                <button type="submit" class="btn btn-primary">Crear Proveedor</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    {{-- Scripts opcionales --}}
@stop