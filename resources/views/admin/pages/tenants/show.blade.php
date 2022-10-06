@extends('adminlte::page')

@section('title', "Detalhes da Empresa {$tenant->name}")

@section('content_header')
    <h1>Detalhes da Empresa <b>{{ $tenant->name }}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <center>
                    <img src="{{ url("storage/{$tenant->logo}") }}" alt="{{ $tenant->name }}" width="90px">
                </center>
                <li>
                    <strong>Plano: </strong> {{ $tenant->plan->name }}
                </li>
                <li>
                    <strong>Nome: </strong> {{ $tenant->name }}
                </li>
                <li>
                    <strong>Url: </strong> {{ $tenant->url }}
                </li>
                <li>
                    <strong>Email: </strong> {{ $tenant->email }}
                </li>
                <li>
                    <strong>CNPJ: </strong> {{ $tenant->cnpj }}
                </li>
                <li>
                    <strong>Ativo: </strong> {{ $tenant->active == 'Y' ? 'SIM' : 'NÃO' }}
                </li>
            </ul>

            <hr>
            <h1>Assinatura</h1>
            <ul>
                <li>
                    <strong>Data assinatura: </strong> {{ $tenant->subscription }}
                </li>
                <li>
                    <strong>Data expiração: </strong> {{ $tenant->expires_at }}
                </li>
                <li>
                    <strong>Identificador: </strong> {{ $tenant->subscription_id }}
                </li>
                <li>
                    <strong>Ativo? </strong> {{ $tenant->subscription_active ? 'SIM' : 'NÃO' }}
                </li>
                <li>
                    <strong>Cancelou? </strong> {{ $tenant->subscription_suspended ? 'SIM' : 'NÃO' }}
                </li>
            </ul>
        </div>
    </div>
@endsection