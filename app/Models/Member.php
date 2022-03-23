<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'id_member';
    public $timestamps = false;
    protected $fillable = ['nama', 'alamat', 'jenis_kelamin', 'no_telfon'];
}