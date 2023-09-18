@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="my-3 p-2 rounded-3 w-full bg-white shadow">
                    <div class="pt-2">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/admin/members') }}">Member</a></li>
                            <li class="breadcrumb-item active">Tambah Member</li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Tambah Member</h2>
                    </div>
                    <div class="panel-body">
                        {!! Form::open([
                            'url' => route('members.store'),
                            'method' => 'post',
                            'files' => 'true',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @include('members._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
