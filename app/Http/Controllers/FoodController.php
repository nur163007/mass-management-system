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
        ]);

        // Check if category already exists
        $existingCategory = Category::where('category_name', $request->category_name)->first();
        if ($existingCategory) {
            return response()->json([
                'success' => true,
                'category' => $existingCategory
            ]);
        }

        $categories = new Category;
        $categories->category_name = $request->category_name;
        $categories->photo = "null"; // Set default value
      

        // dd($categories);
        if ($categories->save()) {
            return response()->json([
                'success' => true,
                'category' => $categories
            ]);
         }
         else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category'
            ]);
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