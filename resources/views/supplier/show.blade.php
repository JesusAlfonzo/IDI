@extends('adminlte::page')

@section('title', $supplier->name)

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="mb-0">
            {{ __('Detalles del Proveedor') }}
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>{{ __('Nombre:') }}</strong>
                        <p class="form-control-plaintext">{{ $supplier->name }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Persona de Contacto:') }}</strong>
                        <p class="form-control-plaintext">{{ $supplier->contact_person }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Teléfono:') }}</strong>
                        <p class="form-control-plaintext">{{ $supplier->phone }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>{{ __('Correo Electrónico:') }}</strong>
                        <p class="form-control-plaintext">{{ $supplier->email }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>{{ __('Dirección:') }}</strong>
                        <p class="form-control-plaintext">{{ $supplier->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop