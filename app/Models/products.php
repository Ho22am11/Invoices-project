<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'products_name',
        'sections_id',
        'description',
        
    ];
    public function sections()
    {
        return $this->belongsTo(sections::class);
    }
}
