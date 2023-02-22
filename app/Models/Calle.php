<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    use HasFactory;
    protected $table = 'calles';
    public $timestamps = false;
    protected $guarded = ['id'];
}
