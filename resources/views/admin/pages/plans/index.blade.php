@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('plans.index') }}">Planos</a></li>
</ol>
<h1>Planos <a href="{{ route('plans.create') }}" class="btn btn-dark">ADD<i class="fas fa-plus-square"></i></a> </h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('plans.search') }}" class="form form-inline" method="POST">
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
                    <th>Preço</th>
                    <th width="300">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td><i>R$</i> {{ number_format($plan->price, 2, ',', '.') }}</td>
                    <td style="width=10px;">
                        <a href="{{ route('details.plan.index', $plan->url) }}" class="btn btn-primary">DETALHES</a>
                        <a href="{{ route('plan.edit', $plan->url) }}" class="btn btn-info">EDITAR</a>
                        <a href="{{ route('plans.show', $plan->url) }}" class="btn btn-warning">VER</a>
                        <a href="{{ route('plans.profiles', $plan->id) }}" class="btn btn-warning"><i class="fas fa-address-book"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        @if (isset($filters))
        {!! $plans->appends($filters)->links() !!}
        @else
        {!! $plans->links() !!}
        @endif
    </div>
</div>
@stop