<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyCategory;
use Str;

class CompanyCategory extends Model
{
    use HasFactory;

    protected $table = "company_category";

    protected $guarded = [];

    public function companies(){
        return $this->hasMany(Company::class, 'category_id', 'id');
    }
}
