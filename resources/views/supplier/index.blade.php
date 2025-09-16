@extends('adminlte::page')

@section('title', 'Lista de Proveedores')

@section('content_header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 id="card_title">
            {{ __('Proveedores') }}
        </h1>
        <div class="float-right">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                {{ __('Crear Nuevo Proveedor') }}
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
                            <th>No</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Persona de Contacto') }}</th>
                            <th>{{ __('Teléfono') }}</th>
                            <th>{{ __('Correo Electrónico') }}</th>
                            <th>{{ __('Dirección') }}</th>
                            <th>{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->contact_person }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td style="width: 150px;">
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                        <a class="btn btn-sm btn-primary" href="{{ route('suppliers.show', $supplier->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                        <a class="btn btn-sm btn-success" href="{{ route('suppliers.edit', $supplier->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar este proveedor?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $suppliers->links() !!}
        </div>
    </div>
@stop