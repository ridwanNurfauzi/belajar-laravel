<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'user_id', 'is_returned'];

    protected $casts = [
        'is_returned' => 'boolean'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeReturned($query)
    {
        return $query->where('is_returned', 1);
    }
    public function scopeBorrowed($query)
    {
        return $query->where('is_returned', 0);
    }
}
