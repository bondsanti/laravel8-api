<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    //หากตั้งชื่อ Models ไม่ตรงกับฐานข้อมูล เราต้องกำหนด Proproties
    //======================================
    //protected $table ='tb_department'; // <- คำสั่งนี้
    //protected $primaryKey ='dep_id';//<- หรือฟิลด์ pk ที่เป็นชื่ออื่น ที่ไม่ไดใช่ id ให้กำหนดแบบนี้
    //protected $keyType='string';//<- ถ้า pk ไม่ใช่ int ให้กำหนดแบบนี้
    //public $incrementing = false;//pk ไม่ได้เป็น auto increment ให้กำหนดปิดแบบนี้
    //======================================
    //หากในตารางฐานข้อมูลไม่มี ฟิลด์ created_ad&updated_at เราต้องปิดมันด้วย
    //public $timestamps = false;



    //relation one to many with officerModel
    public function officers_ref()
    {

        //แต่ละแผนก มีพนักงานคนไหนบ้าง
        //return $this->hasMany(Officer::class); //แบบตามกฏ
        return $this->hasMany(Officer::class,'department_id','id'); //กรณีชื่อฟิลด์ไม่ตรงกฏ
    }


}
