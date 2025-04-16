<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipodocumentoidentidad extends Model
{
    use HasFactory;

    protected $table = 'tipodocumentoidentidad';

    protected $fillable = ['descripcion'];
}
