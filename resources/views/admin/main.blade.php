@extends('layouts.admin')
@section('title')
    Test
@stop
@section('content')
    Hi {{$user->first_name . ' ' . $user->last_name}}
@stop