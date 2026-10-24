@extends('templates.user_templates')

@section('content')
    <p> selamat datang user {{ auth()->user()->name }}, fitur masih dalam tahap pengembangan</p>
@endsection
