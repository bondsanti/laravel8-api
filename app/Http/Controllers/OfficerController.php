<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $officer = Officer::with('department_ref:id,name')->get();
       $officer = Officer::with('department_ref:id,name','user_ref:id,email')->get();
        return response()->json([
            'data'=> $officer
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(),[
                'firstname' => 'required',
                'lastname' => 'required',
                'dob' => 'required',
                'salary' => 'required',
                'user_id' => 'required',
                'department_id' => 'required'
            ],[
                'firstname.required'=>'ป้อนข้อมูลชื่อด้วย',
                'lastname.required'=>'ป้อนข้อมูลสกุลด้วย',
                'salary.required'=>'ป้อนเงินเดือนด้วย',
                'user_id.required'=>'ป้อน UserID',
                'department_id.required'=>'ป้อน DepartmentID'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => [
                        'message' => $validator->errors()
                    ]
                ], 422);
            }

            $officer = new Officer();

            if ($request->has('picture')) { //เช็คค่าว่ามีข้อมูลมาหรือไม่
                $base64_image = $request->picture;
                @list($type, $file_data) = explode(';', $base64_image);
                @list(, $file_data) = explode(',', $file_data);

                $new_filename = uniqid() . '.png';

                if ($file_data != "") {
                    Storage::disk('public')->put('uploads/officer/'.$new_filename, base64_decode($file_data));
                }
                $officer->firstname = $request->firstname;
                $officer->lastname = $request->lastname;
                $officer->dob = $request->dob;
                $officer->salary = $request->salary;
                $officer->user_id = $request->user_id;
                $officer->department_id = $request->department_id;
                $officer->picture = $new_filename;

            } else {
                $officer->firstname = $request->firstname;
                $officer->lastname = $request->lastname;
                $officer->dob = $request->dob;
                $officer->salary = $request->salary;
                $officer->user_id = $request->user_id;
                $officer->department_id = $request->department_id;
            }

            $officer->save();
            DB::commit();

            return response()->json([
                'message' => 'เพิ่มข้อมูลพนักงานเรียบร้อย'
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล',
                'system message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $officer = Officer::find($id);

        if($officer==null){
            return response()->json([
                'errors' => [
                    'message'=>'ไม่พบข้อมูล'
                    ]
            ],404);
        }

            return response()->json([
                'data' =>  $officer
            ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if($id!=$request->id){
            return response()->json([
                'errors' => [
                    'message'=>'ไม่สามารถอัพเดทข้อมูลได้ ID ไม่ถูกต้อง..'
                    ]
            ],400);
        }
            try {
                DB::beginTransaction();

                $officer = Officer::find($id);
                //dd($officer->picture);
                if ($request->has('picture')) { //เช็คค่าว่ามีข้อมูลมาหรือไม่
                    $base64_image = $request->picture;
                    @list($type, $file_data) = explode(';', $base64_image);
                    @list(, $file_data) = explode(',', $file_data);

                    $new_filename = uniqid() . '.png';

                    if ($file_data != "") {

                        if($officer->picture!="nopic"){
                            Storage::disk('public')->delete('uploads/officer/'.$officer->picture);
                            }

                        Storage::disk('public')->put('uploads/officer/'.$new_filename, base64_decode($file_data));
                    }
                    $officer->firstname = $request->firstname;
                    $officer->lastname = $request->lastname;
                    $officer->dob = $request->dob;
                    $officer->salary = $request->salary;
                    $officer->user_id = $request->user_id;
                    $officer->department_id = $request->department_id;
                    $officer->picture = $new_filename;

                } else {
                    $officer->firstname = $request->firstname;
                    $officer->lastname = $request->lastname;
                    $officer->dob = $request->dob;
                    $officer->salary = $request->salary;
                    $officer->user_id = $request->user_id;
                    $officer->department_id = $request->department_id;
                }

                $officer->save();
                DB::commit();

                return response()->json([
                    'message' => 'อัพเดทข้อมูล สำเร็จ!'
                ], 201);

            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล',
                    'system message' => $th->getMessage()
                ], 400);
            }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $officer = Officer::find($id);

        if($officer==null){
            return response()->json([
                'errors' => [
                    'message'=>'ไม่พบข้อมูล'
                    ]
            ],404);
        }


        if($officer->picture!="nopic"){
            Storage::disk('public')->delete('uploads/officer/'.$officer->picture);
            }

        $officer->delete();

            return response()->json([
                'message'=>'ลบข้อมูล สำเร็จ!'
            ],200);

    }
}
