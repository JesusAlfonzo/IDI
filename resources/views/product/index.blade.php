@extends('adminlte::page')

@section('title', 'Lista de Productos')

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 id="card_title">
            {{ __('Productos') }}
        </h1>
        <div class="float-right">
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                {{ __('Crear Nuevo Producto') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('SKU') }}</th>
                            <th>{{ __('Descripción') }}</th>
                            <th>{{ __('Precio') }}</th>
                            <th>{{ __('Stock') }}</th>
                            <th>{{ __('Umbral de Alerta') }}</th>
                            <th>{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->currentStock() }}</td>
                                <td>{{ $product->stock_alert_threshold }}</td>
                                <td style="width: 150px;">
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('products.show', $product->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success" href="{{ route('products.edit', $product->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $products->links() !!}
        </div>
    </div>
@stop