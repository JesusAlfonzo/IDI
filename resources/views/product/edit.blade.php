@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1>Editar Producto</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('products.update', $product->id) }}" role="form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- This will include the form fields --}}
                @include('product.form')

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

{{-- Any additional scripts can go here --}}
@section('js')
    {{-- For example, handling form field visibility or interactions --}}
@stop