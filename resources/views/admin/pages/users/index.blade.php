@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Usuários</a></li>
</ol>
<h1>Usuários <a href="{{ route('users.create') }}" class="btn btn-dark">ADD<i class="fas fa-plus-square"></i></a> </h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('users.search') }}" class="form form-inline" method="POST">
            @csrf
            <input type="text" name="filter" class="form-control" placeholder="Nome" value="{{ $filters['filter'] ?? '' }}">
            <button type="submit" class="btn btn-dark">Filtrar</button>
        </form>
    </div>
    <div class="card-body">

        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th width="300">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td style="width=10px;">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info">EDITAR</a>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning">VER</a>
                        <a href="{{ route('users.roles', $user->id) }}" class="btn btn-info" title="Cargos"><i class="fas fa-address-card"></i> Cargos</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        @if (isset($filters))
        {!! $users->appends($filters)->links() !!}
        @else
        {!! $users->links() !!}
        @endif
    </div>
</div>
@stop