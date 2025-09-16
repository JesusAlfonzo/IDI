@extends('adminlte::page')

@section('title', 'Editar Categoría')

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="mb-0">
            {{ __('Editar Categoría') }}
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
            <form method="POST" action="{{ route('categories.update', $category->id) }}" role="form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                @include('category.form')
            </form>
        </div>
    </div>
@stop