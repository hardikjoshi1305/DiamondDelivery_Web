<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiamondList extends Model
{
    use HasFactory;
    protected $table = "tbl_diamond_list";
    protected $fillable = ['agent', 'party', 'sn_no', 'sell_date','sold_by','client','contact_no','shape','weight','color','clarity','cut','pol','symm','floro','lab','lab_no','mm1','mm2','mm3','table','td','total'];

}
