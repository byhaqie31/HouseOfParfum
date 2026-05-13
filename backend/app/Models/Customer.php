<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

      //Declare Table Name
    protected $table = 'customer';
    
     //Declare PK
    public $primaryKey = 'id';

    public $timestamps = true;
}
