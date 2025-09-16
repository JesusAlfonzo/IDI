@extends('adminlte::page')

@section('title', $category->name)

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="mb-0">
            {{ __('Detalles de la Categoría') }}
        </h1>
        <div class="float-right">
            <a class="btn btn-primary btn-sm" href="{{ route('categories.index') }}">
                {{ __('Volver a la Lista') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
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
    </div>
@stop