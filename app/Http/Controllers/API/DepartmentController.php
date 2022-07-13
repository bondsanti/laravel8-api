<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$dep = Department::all(); // select * form department
        //$dep = Department::find(3);
        //$dep = Department::where('name','like','%IT%')->get();
        //$dep = Department::select('name')->where('id','=',$depID)->get();
        // $dep = Department::select('id','name')->get();//ORM
        // //$dep= DB::select('select * from departments order by id desc');//Query สาย SQL
        // $total = Department::count();
        // return response()->json([
        //     'data' => $dep,
        //     'total' => $total
        // ],200);

        //แบ่งหน้า pagination
        //{{url}}/api/department?page1&page_size=5
        $page_size = request()->query('page_size');
        $pageSizer = $page_size == null ? 5 : $page_size;

       // $dep = Department::select('id','name')->paginate($pageSizer);

        //test relation
        //$dep = Department::with('officers_ref')->get();
         $dep = Department::with('officers_ref')->paginate($pageSizer);

        return response()->json([
                    'data' => $dep
                ],200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:departments',
        ],[
            'name.required'=>'ป้อนข้อมูลชื่อแผนกด้วย',
            'name.unique'=>'ชื่อแผนกซ้ำ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'message' => $validator->errors()
                ]
            ], 422);
        }

        $dep = new Department();
        $dep->name = $request->name;
        $dep->save();
        return response()->json([
            'message'=>'เพิ่มข้อมูล สำเร็จ!',
            'data'=>$dep
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dep = Department::find($id);

        if($dep==null){
            return response()->json([
                'errors' => [
                    'message'=>'ไม่พบข้อมูล'
                    ]
            ],404);
        }

            return response()->json([
                'data' =>  $dep
            ],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
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

        $dep = Department::find($id);
        $dep->name = $request->name;
        $dep->save();

        return response()->json([
            'massage'=>'อัพเดทข้อมูล สำเร็จ!',
            'data'=>$dep
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dep = Department::find($id);

        if($dep==null){
            return response()->json([
                'errors' => [
                    'message'=>'ไม่พบข้อมูล'
                    ]
            ],404);
        }

        $dep->delete();

            return response()->json([
                'message'=>'ลบข้อมูล สำเร็จ!'
            ],200);
    }

    public function search()
    {
        $txtQuery = request()->query('name');
        //$txtQuery = request()->query('nameth');

        $keyword = '%'.$txtQuery.'%';
        $dep = Department::where('name','like',$keyword)->paginate(5);

        if($dep->isEmpty()){
            return response()->json([
                'errors' => [
                    'message'=>'ไม่พบข้อมูล'
                    ]
            ],404);
        }
        return response()->json([
            'data'=>$dep
        ]);

    }
}
