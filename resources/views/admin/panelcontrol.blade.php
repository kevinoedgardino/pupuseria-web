@extends('adminlte::page')

@section('title', 'Panel de Control')

@section('content_header')
    <h1>PANEL DE CONTROL</h1>
@stop

@section('content')
<div id="app">
    <v-app>
        <template>
            <control-panel></control-panel>
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