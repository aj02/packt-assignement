<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'person_prefix',
        'first_name',
        'last_name',
        'active'
    ];
}
