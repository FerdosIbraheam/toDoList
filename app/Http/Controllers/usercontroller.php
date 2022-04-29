<?php

namespace App\Http\Controllers;
use App\Models\usermodel;

use Illuminate\Http\Request;
use DB;

class usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =  DB::table('user')->get();  // select * from users

        return view('user.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("user.create",['title'=>"create user"]);
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
          "name"=>'required',
          "email"=>'required|email|unique:user',
          "password"=>'required|min:6|max:10',
          "image"=>"required|image|mimes:png,jpg"

        ]);
        $FinalName = uniqid() . '.' . $request->image->extension();

        if ($request->image->move(public_path('/userImages'), $FinalName)) {
            $data['image'] = $FinalName;
        }

        $data['password']=bcrypt($data['password']);
        $op =  DB::table('user')->insert($data);
        if($op){
            $message="raw inserted";

        }else{
            $message="Error try again";
        }
        session()->flash('message',$message);
        return redirect(url('/user'));
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
         $title = "Edit Account";

        # Fetch Raw Data ...
        //    $data =  student :: where('id',$id)->get();     // $data[0]->name
        $data =  DB::table('user')->find($id);

        return view('user.edit', ['title' => $title, 'data' => $data]);
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
            "name"     => 'required',
            "email"    => "required|email|unique:users,email," . $id,
            "image"    => "nullable|image|mimes:png,jpg"
        ]);


        # Fetch Raw Data ...
        $rawData = DB::table('user')->find($id);

        if ($request->hasFile('image')) {
            # Rename Image ....
            $FinalName = uniqid() . '.' . $request->image->extension();

            if ($request->image->move(public_path('/userImages'), $FinalName)) {
                $data['image'] = $FinalName;

                unlink(public_path('userImages/' . $rawData->image));
            }
        } else {
            $data['image'] = $rawData->image;
        }


        // update users ste name = $name , email = $email where id = $id

        $op =  DB::table('user')->where('id', $id)->update($data);

        if ($op) {
            $message = 'Raw Updated';
        } else {
            $message = "Error try again";
        }

     # Set Message Session ....
     session()->flash('Message',$message);

     return redirect(url('/user'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           # Fetch RaW Data
           $raw = DB::table('user')->find($id);

           # Delete Raw ....
           $op =   DB::table('user')->where('id', $id)->delete(); // delete from users where id = $id

           if ($op) {

              // unlink(public_path('userImages/' . $raw->image));

               $message = 'Raw Deleted';
           } else {
               $message = 'Error Try Again';
           }

                # Set Message Session ....
                session()->flash('Message',$message);

                return redirect(url('/user'));
    }

    function login(){

        return view('user.login',['title' => "Login Page"]);

    }

    #############################################################################################################################

    function DoLogin(Request $request){
        // code ....

         $data = $this->validate($request, [
               "email"    => "required|email",
               "password" => "required|min:6|max:10"
         ]);

         //dd(auth('userauth')->attempt($data));
           if(auth('userauth')->attempt($data)){

              return redirect(url('/task'));

           }else{

            session()->flash('Message',"Error In Your Data Try Again");

            return back();

           }

    }
    function logout(){

        auth('userauth')->logout();
        return redirect(url('/login'));

      }

}
