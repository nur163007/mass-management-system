<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\foodItem;
use Carbon\Carbon;
use DB;

class FoodItemController extends Controller
{
    public function addFoodItem(){
        $categories = Category::all();
        return view('admin.foodItem.addItem',compact('categories'));
    }

    public function storeFoodItem(Request $request){
        $this->validate($request,[
            'food_category_id' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            'item_photo' => 'required',
        ]);

        $items = new FoodItem;

        $date = Carbon::now()->format('his')+rand(10000,99999);

        // item photo 
        
        if($image = $request->file('item_photo')){
            $extention = $request->file('item_photo')->getClientOriginalExtension();
            $imageName = $date.'.'.$extention;
            $path = public_path('uploads/foodItems');
            $image->move($path,$imageName);
            $items->item_photo = $imageName;
            
        }  
        else{
            
            $items->item_photo = "null";
        }
        $items->food_category_id = $request->food_category_id;
        $items->item_name = $request->item_name;
        $items->item_description = $request->item_description;
      

        
        if ($items->save()) {
            return response()->json($items);
         }
         else{
            return response()->json("error");
         }  
    }

    public function viewFoodItem(){
        $items = DB::table('food_items')->join('food_categories','food_items.food_category_id','food_categories.id')->select('food_items.*','food_categories.category_name')->get();
//   dd($items);
        return view('admin.foodItem.viewItem',compact('items'));
    }
    
    // public function showFoodItem(){
        
    //     // $data['food_categories'] = Category::all();  
    //     // $data['food_items'] = FoodItem::all();
    //     $items = DB::table('food_items')->join('food_categories','food_items.food_category_id','food_categories.id')->select('food_items.*','food_categories.category_name')->get();
    // //    dd($items);
    //     return response()->json($items);
    // }

    public function deleteFoodItem($id){
        $items = FoodItem::findOrFail($id);
         
        if($items){
            if(file_exists('uploads/foodItems/'.$items->item_photo) AND !empty($items->item_photo)){
                unlink('uploads/foodItems/'.$items->item_photo);
            }
           
            $items->delete();
            return redirect()->back()->with('success','Item successfully deleted.');
        }else{
            return redirect()->back()->with('error','Something Error Found !, Please try again.');
          
        }
     }

     public function editFoodItem($id){
        $items = DB::table('food_items')->join('food_categories','food_items.food_category_id','food_categories.id')->select('food_items.*','food_categories.category_name')->where('food_items.id',$id)->first();
    // dd($items);
       $categories = Category::all();
        return view('admin.foodItem.editItem',compact('items','categories'));
    }

    public function updateFoodItem(Request $request){
         // dd($request);
         $this->validate($request,[
            'food_category_id' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
        ]);

        $id = $request->itemID;
        // dd($id);
        $items = FoodItem::findOrFail($id);


        $date = Carbon::now()->format('his')+rand(10000,99999);

        // item photo 
        
        if($image = $request->file('item_photo')){
            $extention = $request->file('item_photo')->getClientOriginalExtension();
            $imageName = $date.'.'.$extention;
            $path = public_path('uploads/foodItems');
            $image->move($path,$imageName);

            if($items){
                if(file_exists('uploads/foodItems/'.$items->item_photo) AND !empty($items->item_photo)){
                    unlink('uploads/foodItems/'.$items->item_photo);
                }
            }
            $items->item_photo = $imageName;
            
        }  
        else{
            
            $items->item_photo = $items->item_photo;
        }
        
        $items->food_category_id = $request->food_category_id;
        $items->item_name = $request->item_name;
        $items->item_description = $request->item_description;
        // dd($foods);
        if ($items->save()) {
            return response()->json("success");
         }
         else{
            return response()->json("error");
         }  
    }
}
