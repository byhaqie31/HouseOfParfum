<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

      //Declare Table Name
    protected $table = 'cart';
    
     //Declare PK
    public $primaryKey = 'id';

    public $timestamps = true;
}
