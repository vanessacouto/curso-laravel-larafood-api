@extends('adminlte::page')

@section('title', 'Detalhes do Cargo')

@section('content_header')
    <h1>Detalhes do Cargo <b>{{ $role->name }}</b> </h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
       <ul>
           <li>
               <strong>Nome: </strong> {{ $role->name }}
           </li>
           <li>
               <strong>Descrição: </strong> {{ $role->description }}
           </li>
       </ul>

       @include('admin.includes.alerts')
       
       <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
           @csrf
           @method('DELETE')
           <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>DELETAR O CARGO <i>{{ $role->name }}</i></button>
       </form>
       
    </div>
</div>
@stop