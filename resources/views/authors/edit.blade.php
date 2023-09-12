@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/authors') }}">Penulis</a></li>
                    <li class="active breadcrumb-item">Ubah Penulis</li>
                </ul>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Ubah Penulis</h2>
                    </div>
                    <div class="panel-body">
                        {!! Form::model($author, [
                            'url' => route('authors.update', $author->id),
                            'method' => 'put',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @include('authors._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
