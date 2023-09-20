<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data buku</title>
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        .container {
            margin-left: 3rem;
            margin-right: 3rem;
        }
    </style>
</head>

<body>
    <div>
        <div>
            <h1 class="text-center">
                Data Buku
            </h1>
        </div>
        <div class="container">
            <div style="width: 100%;">
                <table style="width: 100%;" cellpadding="2">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Jumlah</th>
                            <th>Stok</th>
                            <th>Penulis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td> {{ $book->title }} </td>
                                <td> {{ $book->amount }} </td>
                                <td> {{ $book->stock }} </td>
                                <td> {{ $book->author->name }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
