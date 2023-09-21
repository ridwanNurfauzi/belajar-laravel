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
                            <li class="breadcrumb-item"><a href="{{ url('/admin/books/create') }}">Tambah Buku</a></li>
                            <li class="breadcrumb-item active">Review Buku</li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Review Buku</h2>
                    </div>
                    <div class="panel-body">
                        <p> <a class="btn btn-success" href="{{ url('/admin/books') }}">Selesai</a> </p>
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Jumlah</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author->name }}</td>
                                        <td>{{ $book->amount }}</td>
                                        <td>
                                            {!! Form::open([
                                                'url' => route('books.destroy', $book->id),
                                                'id' => 'form-' . $book->id,
                                                'method' => 'delete',
                                                'data-confirm' => 'Yakin menghapus ' . $book->title . '?',
                                                'class' => 'form-inline js-review-delete',
                                            ]) !!}
                                            {!! Form::submit('Hapus', ['class' => 'btn btn-xs btn-danger']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p> <a class="btn btn-success" href="{{ url('/admin/books') }}">Selesai</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
