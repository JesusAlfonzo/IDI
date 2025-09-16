@extends('adminlte::page')

@section('title', 'Detalles de ' . $supplier->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">
            {{ __('Detalles del Proveedor') }}
        </h1>
        <div class="float-right">
            <a class="btn btn-secondary btn-sm" href="{{ route('suppliers.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('Volver a la Lista') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Información del Proveedor') }}</h3>
            <div class="card-tools">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
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
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-success">
                <i class="fas fa-edit"></i> {{ __('Editar Proveedor') }}
            </a>
        </div>
    </div>
@stop