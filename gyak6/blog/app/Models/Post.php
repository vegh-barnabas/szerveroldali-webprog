<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
        // return $this->belongsTo(User::class, 'author_id'); // ha máshogy hívnák a mezőt
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
