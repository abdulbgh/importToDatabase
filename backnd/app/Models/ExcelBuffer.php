<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelBuffer extends Model
{
    use HasFactory;
    protected $table = 'buffers';
    protected $guarded = [''];
}
