<?php

namespace App\Http\Controllers;
use App\Models\usermodel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class taskcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {

          $this->middleware(['checkuser'],['except' => ["index","create","store"]]);
          $this->middleware(['checkDate'],['except' => ["index","create","store","edit","update"]]);

    }
    public function index()
    {
        $data = DB :: table('task')->join('user', 'task.user_id','=','user.id')->select('task.*','user.name  as userName')->orderby('id','desc')->get();


        return view('task.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("task.create",['title'=>"create task"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$this->validate($request,[
            "title"=>'required',
            "content"=>'required|min:20',
            "start_date"=>"required|date|after_or_equal:today",
            "end_date"=>"required|date|after_or_equal:today",
            "image"=>"required|image|mimes:png,jpg"

          ]);
          $FinalName = uniqid() . '.' . $request->image->extension();

          if ($request->image->move(public_path('/taskImages'), $FinalName)) {
              $data['image'] = $FinalName;
          }
          $data['user_id'] = auth('userauth')->user()->id;

          $data['start_date'] = strtotime($request->start_date);
          $data['end_date'] = strtotime($request->end_date);
          $op=DB::table('task')->insert($data);
          if($op){
              $message="raw inserted";

          }else{
              $message="Error try again";
          }
          session()->flash('message',$message);
          return redirect(url('/task'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB :: table('task')->find($id);

        return view('task.edit',['data' => $data, "title" => "Edit task"]);
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
        $data = $this->validate($request, [
            "title"   => "required|min:15",
            "content" => "required|min:20",
            "start_date" => "required|date|after_or_equal:today",
            "end_date" => "required|date|after_or_equal:today",
            "image"   => "nullable|image|mimes:png,jpg"
        ]);

        $data['start_date'] = strtotime($request->start_date);
        $data['end_date'] = strtotime($request->end_date);
        # Fetch Raw Data ....
        $rawData = DB::table('task')->find($id);

        if ($request->hasFile('image')) {
            # Rename Image ....
            $FinalName = uniqid() . '.' . $request->image->extension();

            if ($request->image->move(public_path('/taskImages'), $FinalName)) {
                $data['image'] = $FinalName;

                unlink(public_path('task/' . $rawData->image));
            }
        } else {
            $data['image'] = $rawData->image;
        }

        # Update Data .....
        $op = DB::table('task')->where('id', $id)->update($data);


        if ($op) {
            $message = "Raw Updated";
        } else {
            $message = "Error Try Again";
        }

        session()->flash('Message', $message);

        return redirect(url('/task'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $op = DB :: table('task')->where('id',$id)->delete();

        if ($op) {
            $message = "Raw Removed";
        } else {
            $message = "Error Try Again";
        }

        session()->flash('Message', $message);

        return redirect(url('/task'));
    }
}
