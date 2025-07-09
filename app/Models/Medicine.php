<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'company',
        'price',
        'quantity',
        'expiry_date',
        'category_id',
        'created_by'
    ];


    public function category() {
        return $this->belongsTo(Category::class);
     }

    public function creator() {
        return $this->belongsTo(User::class,'created_by');
     }
}
