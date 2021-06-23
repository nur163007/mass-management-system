<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;

class AddmemberController extends Controller
{
    public function add(){
        return view('admin.member.add-member');
    }

    public function store(Request $request){
        // 
        $this->validate($request,[
            'full_name' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'email' => 'required',
            'photo' => 'required',
            'nid_photo' => 'required',
        ]);

        $members = new Member;

        $date = Carbon::now()->format('his')+rand(1000,9999);

        // profile photo 
        
        if($image = $request->file('photo')){
            $extention = $request->file('photo')->getClientOriginalExtension();
            $imageName = $date.'.'.$extention;
            $path = public_path('uploads/members/profile');
            $image->move($path,$imageName);
            $members->photo = $imageName;
            
        }  
        else{
            
            $members->photo = "null";
        }

        // nid photo 

        if($image2 = $request->file('nid_photo')){
            $extention2 = $request->file('nid_photo')->getClientOriginalExtension();
            $imageName2 = $date.'.'.$extention2;
            $path2 = public_path('uploads/members/nid');
            $image2->move($path2,$imageName2);
            $members->nid_photo = $imageName2;
            // dd($request['photo']);
        }  
        else{
            $members->nid_photo = "null";
        }
        $members->full_name = $request->full_name;
        $members->phone_no = $request->phone_no;
        $members->address = $request->address;
        $members->email = $request->email;
// dd($members);
        if ($members->save()) {
            return response()->json(["members" => $members]);
         }
         else{
            return response()->json("error");
         }        
    }

public function view(){
    return view('admin.member.view_members');
}

    public function showdata(){

       $members = Member::all();

       return response()->json($members);
    }

        public function delete($id){
            $members = Member::findOrFail($id);
         
            if($members){
                if(file_exists('uploads/members/profile/'.$members->photo) AND !empty($members->photo)){
                    unlink('uploads/members/profile/'.$members->photo);
                }
                if(file_exists('uploads/members/nid/'.$members->nid_photo) AND !empty($members->nid_photo)){
                    unlink('uploads/members/nid/'.$members->nid_photo);
                }
             
                $members->delete();
                return response()->json('success');
            }else{
                return response()->json('error');
            }
        }

        public function edit($id){
            $members = Member::findOrFail($id);
          
            // dd($id);

            return view('admin.member.edit-member',compact('members'));
        }

        public function update(Request $request){
        //   dd($request->all());
            $this->validate($request,[
                'full_name' => 'required',
                'phone_no' => 'required',
                'address' => 'required',
                'email' => 'required',
                // 'photo' => 'required',
                // 'nid_photo' => 'required',
            ]);
    
            $id = $request->memberID;
            // dd($id);
            $members = Member::findOrFail($id);
    
            $date = Carbon::now()->format('his')+rand(1000,9999);
    
            // profile photo 
            
            if($image = $request->file('photo')){
                $extention = $request->file('photo')->getClientOriginalExtension();
                $imageName = $date.'.'.$extention;
                $path = public_path('uploads/members/profile');
                $image->move($path,$imageName);

                if(file_exists('uploads/members/profile/'.$members->photo) AND !empty($members->photo)){
                    unlink('uploads/members/profile/'.$members->photo);
                }

                $members->photo = $imageName;
                
            }  
            else{
                
                $members->photo = $members->photo;
            }
    
            // nid photo 
    
            if($image2 = $request->file('nid_photo')){
                $extention2 = $request->file('nid_photo')->getClientOriginalExtension();
                $imageName2 = $date.'.'.$extention2;
                $path2 = public_path('uploads/members/nid');
                $image2->move($path2,$imageName2);

                if(file_exists('uploads/members/nid/'.$members->nid_photo) AND !empty($members->nid_photo)){
                    unlink('uploads/members/nid/'.$members->nid_photo);
                }
                
                $members->nid_photo = $imageName2;
                // dd($request['photo']);
            }  
            else{
                $members->nid_photo = $members->nid_photo;
            }
            $members->full_name = $request->full_name;
            $members->phone_no = $request->phone_no;
            $members->address = $request->address;
            $members->email = $request->email;
    // dd($members);
            if ($members->save()) {
                return response()->json("success");
             }
             else{
                return response()->json("error");
             }  
        }

}
