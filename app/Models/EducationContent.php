<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EducationContent extends Model
{
    protected $fillable = ['judul', 'kategori', 'konten', 'ringkasan', 'tipe', 'quiz_data', 'urutan', 'is_published'];
    protected function casts(): array { return ['quiz_data' => 'array', 'is_published' => 'boolean']; }
}
