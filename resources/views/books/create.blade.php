@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="my-3 p-2 rounded-3 w-full bg-white shadow">
                    <div class="pt-2">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/admin/books') }}">Buku</a></li>
                            <li class="breadcrumb-item active">Tambah Buku</li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Tambah Buku</h2>
                    </div>
                    <div class="panel-body">
                        {{-- {!! Form::open([
                            'url' => route('books.store'),
                            'method' => 'post',
                            'files' => 'true',
                            'class' => 'form-horizontal',
                        ]) !!}
                        @include('books._form') 
                        {!! Form::close() !!} --}}

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="nav-item">
                                <button class="nav-link active" data-bs-target="#form" aria-controls="form" role="tab" data-bs-toggle="tab">
                                    <i class="fa fa-pencil-square-o"></i> Isi Form
                                </button>
                            </li>
                            <li role="presentation" class="nav-item">
                                <button class="nav-link" data-bs-target="#upload" aria-controls="upload" role="tab" data-bs-toggle="tab">
                                    <i class="fa fa-cloud-upload"></i> Upload Excel
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active" id="form">
                                {!! Form::open([
                                    'url' => route('books.store'),
                                    'method' => 'post',
                                    'files' => 'true',
                                    'class' => 'form-horizontal',
                                ]) !!}
                                @include('books._form')
                                {!! Form::close() !!}
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="upload">
                                {!! Form::open([
                                    'url' => route('import.books'),
                                    'method' => 'post',
                                    'files' => 'true',
                                    'class' => 'form-horizontal',
                                ]) !!}
                                @include('books._import')
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
