@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1 class="fs-1">PEDIDOS</h1>
@stop

@section('content')
<div id="app">
    <v-app>
        <template>
            <pedido></pedido>
        </template>
    </v-app>
</div>
@stop

@section('css')
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
@stop

@section('js')
    <script src="{{asset('js/app.js')}}"></script>
@stop