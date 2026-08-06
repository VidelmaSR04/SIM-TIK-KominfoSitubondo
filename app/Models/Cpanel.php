<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpanel extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'domain', 'ip_local', 'ip_public', 'status'];
}