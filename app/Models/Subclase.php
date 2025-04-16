<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subclase extends Model
{
    use HasFactory;

    protected $table = 'subclase';

    protected $fillable = ['descripcion'];
}
