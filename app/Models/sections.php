<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 
use Laravel\Sanctum\HasApiTokens;
class sections extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'section_name',
        'description',
        'who_created',
    ];
}
