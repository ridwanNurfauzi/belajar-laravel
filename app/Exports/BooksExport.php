<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class BooksExport implements FromCollection
{
    private $var;
    public function __construct($var = null)
    {
        $this->var = $var;
    }

    public function collection()
    {
        return $this->var;
    }
}
