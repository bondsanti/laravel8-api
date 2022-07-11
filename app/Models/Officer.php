<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table ='officers';

    //เพิ่ม getter ไป Json
    protected $appends = ['fullname','age','picture_url'];

    //ไม่แสดงฟิลด์
    protected $hidden = ['firstname','lastname','picture'];


    //getters (Accessor) สร้างฟิลด์เอง เพื่อส่งให้ ไคเอ้น
   public function getFullnameAttribute()//fullname
   {
    return $this->firstname.' '.$this->lastname;
   }

    public function getAgeAttribute()//age
    {
        return now()->diffInYears($this->dob);
    }

    public function getPictureUrlAttribute() //picture_url
    {
        return asset('storage/uploads/officer').'/'.$this->picture;
    }

        //many to one
    public function department_ref()
    {
        //reverse data พนักงานแต่ละคน มีแผนกไหนบ้าง
        //return $this->belongsTo(Department::class);//ตามกฏ
        return $this->belongsTo(Department::class,'department_id','id'); //กรณีชื่อฟิลด์ไม่ตรงกฏ
    }
    // revert one to one
    public function user_ref(){
        //return $this->belongsTo(User::class);//ตามกฏ
        return $this->belongsTo(User::class,'user_id','id');
    }

}
