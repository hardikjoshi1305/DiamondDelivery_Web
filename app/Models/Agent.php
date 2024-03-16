<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;



class Agent extends Model
{
    use Notifiable;

    use HasFactory,HasApiTokens, Notifiable;
    protected $table = "tbl_agent";
}
