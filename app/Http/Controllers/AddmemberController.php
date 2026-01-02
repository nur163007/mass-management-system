<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AddmemberController extends Controller
{
    public function add(){
        // Only Super Admin can add members
        $loggedInRole = session('role');
        if($loggedInRole != 1){
            return redirect()->route('admin.view-member')->with('error', 'Only Super Admin can add members.');
        }
        
        return view('admin.member.add-member');
    }

    public function store(Request $request){
        // Only Super Admin can create members
        $loggedInRole = session('role');
        if($loggedInRole != 1){
            return response()->json(['error' => 'Only Super Admin can create members.']);
        }

        $this->validate($request,[
            'full_name' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'email' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'required|in:2,3', // Manager (2) or User (3)
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
        $members->password = Hash::make("12345678");
        $members->role_id = $request->role_id;
        $members->role_name = $request->role_id == 2 ? "Manager" : "User";
        // All admin-created members are active
        $members->status = "1";
        
        // dd($members);
    
         if ($members->save()) {
            return response()->json(["members" => $members]);
         }
         else{
            return response()->json("error");
         }    

    }

    public function view(){
        // Show all members except Super Admin (role_id = 1)
        // Only Manager (2) and User (3) should be shown
        $members = DB::table('members')
            ->select('members.*')
            ->whereIn('role_id', [2, 3]) // Only Manager and User
            ->orderBy('id', 'asc') // Order by ID ascending
            ->get();
        // dd($members);
        return view('admin.member.view_members',compact('members'));
    }

    public function memberStatus($id, $status){
        // Only Super Admin can update member status
        $loggedInRole = session('role');
        if($loggedInRole != 1){
            return response()->json(['error' => 'Only Super Admin can update member status.'], 403);
        }

        // dd($id);
        $active = Member::findOrFail($id);
        // Cannot change Super Admin status
        if($active->role_id == 1){
            return response()->json(['error' => 'Cannot change Super Admin status.'], 403);
        }
        
        $active->status= $status; 

        if($active->save()){
            return response()->json(['message'=> 'Success']);
        }
    }

    // public function showdata(){

    //    $members = Member::all();

    //    return response()->json($members);
    // }

        public function delete($id){
          // Only Super Admin can delete members
          $loggedInRole = session('role');
          if($loggedInRole != 1){
              return redirect()->back()->with('error', 'Only Super Admin can delete members.');
          }

          // echo "clicked";
            $members = Member::findOrFail($id);
            // Cannot delete Super Admin
            if($members->role_id == 1){
                return redirect()->back()->with('error', 'Cannot delete Super Admin.');
            }
         // dd($members);
            if($members){
                if(file_exists('uploads/members/profile/'.$members->photo) AND !empty($members->photo)){
                    unlink('uploads/members/profile/'.$members->photo);
                }
                if(file_exists('uploads/members/nid/'.$members->nid_photo) AND !empty($members->nid_photo)){
                    unlink('uploads/members/nid/'.$members->nid_photo);
                }
             
                $members->delete();
                 return redirect()->back()->with('success','Member successfully deleted.');
            }else{
                 return redirect()->back()->with('error','Something Error Found !, Please try again.');
            }
        }

        public function edit($id){
            // Only Super Admin can edit members
            $loggedInRole = session('role');
            if($loggedInRole != 1){
                return redirect()->back()->with('error', 'Only Super Admin can edit members.');
            }

            $members = Member::findOrFail($id);
            // Cannot edit Super Admin
            if($members->role_id == 1){
                return redirect()->back()->with('error', 'Cannot edit Super Admin.');
            }
          
            // dd($id);

            return view('admin.member.edit-member',compact('members'));
        }

    /**
     * Change member role (only Super Admin can change roles)
     */
    public function changeRole(Request $request, $id)
    {
        try {
            // Only Super Admin can change roles
            $loggedInRole = session('role');
            if($loggedInRole != 1){
                return response()->json(['error' => 'Only Super Admin can change member roles.'], 403);
            }

            $this->validate($request, [
                'role_id' => 'required|integer|in:2,3', // Manager (2) or User (3)
            ]);

            $member = Member::findOrFail($id);
            // Cannot change Super Admin's role
            if($member->role_id == 1){
                return response()->json(['error' => 'Cannot change Super Admin role.'], 403);
            }

            $newRole = (int)$request->role_id; // Ensure integer
            $currentRole = (int)$member->role_id;

            DB::beginTransaction();
            try {
                // Case 1: If changing User to Manager (newRole = 2, currentRole = 3)
                // First, find existing Manager and change to User (role_id = 3, role_name = 'User')
                // Then update current User to Manager
                // Only one Manager can exist at a time, so if any Manager exists, change them to User first
                if($newRole == 2 && $currentRole == 3) {
                    // Find existing Manager (if any) - check all Managers regardless of status
                    // Only one Manager allowed, so find any existing Manager and change to User
                    $existingManager = Member::where('role_id', 2)
                        ->where('id', '!=', $id)
                        ->first();
                    
                    if($existingManager) {
                        // Change existing Manager to User (role_id = 3, role_name = 'User')
                        $existingManager->role_id = 3;
                        $existingManager->role_name = 'User';
                        $existingManager->save();
                    }
                }
                
                // Case 2: If changing Manager to User (newRole = 3, currentRole = 2)
                // Just change directly - role_id = 3, role_name = 'User'
                
                // Update the member's role
                $member->role_id = $newRole;
                $member->role_name = $newRole == 2 ? 'Manager' : 'User';
                $member->save();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Role changed successfully',
                    'role_id' => $newRole,
                    'role_name' => $member->role_name
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Role change error: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to change role: ' . $e->getMessage()], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Role change error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

        public function update(Request $request){
        // Only Super Admin can update members
        $loggedInRole = session('role');
        if($loggedInRole != 1){
            return redirect()->back()->with('error', 'Only Super Admin can update members.');
        }

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
            // Cannot update Super Admin
            if($members->role_id == 1){
                return redirect()->back()->with('error', 'Cannot update Super Admin.');
            }
    
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
