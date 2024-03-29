<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    private $var;
    public function __construct($var = [[]])
    {
        $this->var = $var;
    }

    public function collection()
    {
        return $this->var;
    }

    public function headings(): array
    {
        return ['Judul', 'id penulis', 'jumlah', 'cover', 'dibuat ', 'diupdate', 'penulis'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function map($book): array
    {
        if ($book != null)
            return [
                $book->title,
                $book->author_id,
                $book->amount,
                $book->cover,
                $book->created_at,
                $book->updated_at,
                $book->author->name
            ];
        else
            return [];
    }
}
