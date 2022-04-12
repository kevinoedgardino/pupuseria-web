@extends('adminlte::page')

@section('title', 'Panel de Cocina')

@section('content_header')
    <h1 class="fs-1">PANEL DE COCINA</h1>
@stop

@section('content')
<div id="app">
    <v-app>
        <template>
            <control-cocina></control-cocina>
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