<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyCategory;
use Str;

class CompanyCategory extends Model
{
    use HasFactory;

    protected $table = "company_categorys";

    protected $guarded = [];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($post) {
    //         $post->{$post->getKeyName()} = (string) Str::uuid();
    //     });
    // }

    // public function getIncrementing()
    // {
    //     return false;
    // }

    // public function getKeyType()
    // {
    //     return 'string';
    // }

    public function companies(){
        return $this->hasMany(Company::class, 'category_id', 'id');
    }
}
