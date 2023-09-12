@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="my-3 p-2 rounded-3 w-full bg-white shadow">
                    <div class="pt-2">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Penulis</li>
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panelheading">
                        <h2 class="panel-title">Penulis</h2>
                    </div>

                    <div class="panel-body">
                        <p>
                            <a href="{{ route('authors.create') }}" class="btn btn-primary">
                                Tambah
                            </a>
                        </p>
                        {!! $html->table(['class' => 'table-striped']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection
