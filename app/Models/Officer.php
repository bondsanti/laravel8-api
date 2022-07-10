<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table ='officers';

        //many to one
    public function department_ref()
    {
        //reverse data พนักงานแต่ละคน มีแผนกไหนบ้าง
        //return $this->belongsTo(Department::class);//ตามกฏ
        return $this->belongsTo(Department::class,'department_id','id'); //กรณีชื่อฟิลด์ไม่ตรงกฏ
    }
}
