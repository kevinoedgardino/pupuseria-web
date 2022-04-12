@extends('adminlte::page')

@section('title', 'Ordenar')

@section('content_header')
    <h1 class="fs-1">ORDENAR</h1>
@stop

@section('content')
<div id="app">
    <v-app>
        <template>
            <ordenar></ordenar>
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