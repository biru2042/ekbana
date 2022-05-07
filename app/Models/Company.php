<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyCategory;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    public function category(){
        return $this->belongsTo(CompanyCategory::class, 'category_id', 'id');
    }
}
