@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Nuevo Producto</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" role="form" enctype="multipart/form-data">
                @csrf
                
                {{-- Aquí se incluye el formulario con los campos --}}
                @include('product.form')

                <button type="submit" class="btn btn-primary">Crear</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

{{-- Si necesitas scripts adicionales, puedes agregarlos aquí --}}
@section('js')
    {{-- Por ejemplo, un script para manejar la visibilidad de campos --}}
@stop