<?php


namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksTemplate implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    private $var;
    public function __construct()
    {
        $this->var = Book::all();
    }

    public function collection()
    {
        return $this->var;
    }

    public function headings(): array
    {
        return ['Judul', 'penulis', 'jumlah'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function map($book): array
    {
        return [];
    }
}
