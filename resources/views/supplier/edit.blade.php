@extends('adminlte::page')

@section('title', 'Editar Proveedor')

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="mb-0">
            {{ __('Editar Proveedor') }}
        </h1>
        <div class="float-right">
            <a class="btn btn-primary btn-sm" href="{{ route('suppliers.index') }}">
                {{ __('Volver a la Lista') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}" role="form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Esto incluirá los campos del formulario --}}
                @include('supplier.form')

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

{{-- Aquí puedes agregar scripts adicionales si los necesitas --}}
@section('js')
    {{-- Por ejemplo, un script para manejar la visibilidad de campos --}}
@stop