<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Sms extends Model
{
    use HasFactory;
    use softDeletes;

    public $timestamps = false;
    // protected $table =;
    // protected $primaryKey = ;

    protected $fillable = [
        'msg','sender_name','recipient_number'
    ];


}
