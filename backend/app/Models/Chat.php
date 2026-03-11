<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    //Declare Table Name
    protected $table = 'chat';

    //Declare PK
    public $primaryKey = 'id';

    public $timestamps = true;
}
