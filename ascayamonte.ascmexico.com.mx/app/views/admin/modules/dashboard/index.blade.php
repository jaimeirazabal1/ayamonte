@extends('admin.layouts.master')


@section('content')



@stop

@section('javascript')
    <!-- Load and execute javascript code used only in this page -->
    <script src="{{ asset('assets/admin/js/pages/readyDashboard.js') }}"></script>
    <script>$(function(){ ReadyDashboard.init(); });</script>
@stop