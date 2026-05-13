<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

      //Declare Table Name
      protected $table = 'order';
    
      //Declare PK
     public $primaryKey = 'id';
 
     public $timestamps = true;
}
