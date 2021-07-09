<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;

class FoodController extends Controller
{
    public function addCategory(){
        return view('admin.foodCategory.addCategory');
    }

    public function storeCategory(Request $request){
        // dd($request);
        $this->validate($request,[
            'category_name' => 'required',
            'photo' => 'required',
        ]);

        $categories = new Category;

        $date = Carbon::now()->format('his')+rand(10000,99999);

        // item photo 
        
        if($image = $request->file('photo')){
            $extention = $request->file('photo')->getClientOriginalExtension();
            $imageName = $date.'.'.$extention;
            $path = public_path('uploads/category');
            $image->move($path,$imageName);
            $categories->photo = $imageName;
            
        }  
        else{
            
            $categories->photo = "null";
        }
        $categories->category_name = $request->category_name;
      

        // dd($categories);
        if ($categories->save()) {
            return response()->json($categories);
         }
         else{
            return response()->json("error");
         }  
    }

    public function viewCategory(){
        $categories = Category::all();
        return view('admin.foodCategory.viewCategory',compact('categories'));
    }

    // public function showCategory(){
       
    //     $categories = Category::all();

    //     return response()->json($categories);
    // }

     public function deleteCategory($id){
        $categories = Category::findOrFail($id);
         
        if($categories){
            if(file_exists('uploads/category/'.$categories->photo) AND !empty($categories->photo)){
                unlink('uploads/category/'.$categories->photo);
            }
           
            $categories->delete();
            return redirect()->back()->with('success','Category successfully deleted.');
        }else{
            return redirect()->back()->with('error','Something Error Found !, Please try again.');
           
        }
     }

     public function editCategory($id){
        $categories = Category::findOrFail($id);
      
        // dd($id);

        return view('admin.foodCategory.editCategory',compact('categories'));
    }

    public function updateCategory(Request $request){
        // dd($request);
        $this->validate($request,[
            'category_name' => 'required', 
        ]);

        $id = $request->categoryID;
        // dd($id);
        $categories = Category::findOrFail($id);


        $date = Carbon::now()->format('his')+rand(10000,99999);

        // item photo 
        
        if($image = $request->file('photo')){
            $extention = $request->file('photo')->getClientOriginalExtension();
            $imageName = $date.'.'.$extention;
            $path = public_path('uploads/category');
            $image->move($path,$imageName);

            if($categories){
                if(file_exists('uploads/category/'.$categories->photo) AND !empty($categories->photo)){
                    unlink('uploads/category/'.$categories->photo);
                }
            }
            $categories->photo = $imageName;
            
        }  
        else{
            
            $categories->photo = $categories->photo;
        }
        $categories->category_name = $request->category_name;

        // dd($foods);
        if ($categories->save()) {
            return response()->json("success");
         }
         else{
            return response()->json("error");
         }  

    }

}