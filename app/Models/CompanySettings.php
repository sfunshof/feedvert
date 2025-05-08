<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'companyID',
        'companyName',
        'currency',
        'tax',
        'timezone'
    ];
    protected $table = 'companysettingstable';
    
    public function users()
    {
        return $this->hasMany(User::class, 'companyID', 'companyID');
    }
}
