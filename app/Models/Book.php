<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author_id', 'amount'];

    public static function boot()
    {
        parent::boot();
        self::updating(function ($book) {
            if ($book->amount < $book->borrowed) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Jumlah buku $book->title harus lebih dari/sama dengan " . $book->borrowed
                ]);
                return false;
            }
        });

        self::deleting(function ($book) {
            if ($book->borrowLogs()->count() > 0) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Buku $book->title sudah pernah dipinjam."
                ]);
                return false;
            }
        });
    }
    public function getBorrowedAttribute()
    {
        return $this->borrowLogs()->borrowed()->count();
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function borrowLogs()
    {
        return $this->hasMany(BorrowLog::class);
    }
    public function getStockAttribute()
    {
        $borrowed = $this->borrowLogs()->borrowed()->count();
        $stock = $this->amount - $borrowed;
        return $stock;
    }
}
