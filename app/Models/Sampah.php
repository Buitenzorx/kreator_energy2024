<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    use HasFactory;
    protected $table = 'sampah'; // Pastikan nama tabel yang benar

    protected $fillable = ['kategori', 'jarak', 'tegangan', 'arus','daya'];
}
