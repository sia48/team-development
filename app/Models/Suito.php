<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Psr7\Request;

class Suito extends Model
{
    use HasFactory;

    protected $fillable = [
        'category','money','datetime','suito','flag',
    ];
}
