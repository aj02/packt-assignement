<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'isbn13',
        'title',
        'publication_date',
        'concept',
        'language',
        'tool',
        'vendor'
    ];
}
