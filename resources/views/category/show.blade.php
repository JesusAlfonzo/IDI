@extends('adminlte::page')

@section('title', 'Detalles de ' . $category->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">
            {{ __('Detalles de la Categoría') }}
        </h1>
        <div class="float-right">
            <a class="btn btn-secondary btn-sm" href="{{ route('categories.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('Volver a la Lista') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Información de la Categoría') }}</h3>
            <div class="card-tools">
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
                <strong>{{ __('Nombre:') }}</strong>
                <p class="form-control-plaintext">{{ $category->name }}</p>
            </div>
            <div class="form-group mb-3">
                <strong>{{ __('Descripción:') }}</strong>
                <p class="form-control-plaintext">{{ $category->description }}</p>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-success">
                <i class="fas fa-edit"></i> {{ __('Editar Categoría') }}
            </a>
        </div>
    </div>
@stop