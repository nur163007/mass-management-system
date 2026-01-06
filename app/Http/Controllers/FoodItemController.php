<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\FoodItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FoodItemController extends Controller
{
    public function addFoodItem(){
        return view('admin.foodItem.addItem');
    }

    public function storeFoodItem(Request $request){
        $this->validate($request,[
            'food_category_id' => 'nullable',
            'item_name' => 'required',
            'item_description' => 'nullable',
            'item_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if item already exists (with or without category)
        $query = FoodItem::where('item_name', $request->item_name);
        if ($request->food_category_id) {
            $query->where('food_category_id', $request->food_category_id);
        } else {
            $query->whereNull('food_category_id');
        }
        $existingItem = $query->first();
        
        if ($existingItem) {
            return response()->json([
                'success' => true,
                'item' => $existingItem
            ]);
        }

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
        $items->food_category_id = $request->food_category_id ?? null;
        $items->item_name = $request->item_name;
        $items->item_description = $request->item_description ?? "Quick created from expense form";
      

        
        if ($items->save()) {
            return response()->json([
                'success' => true,
                'item' => $items
            ]);
         }
         else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item'
            ]);
         }  
    }

    public function viewFoodItem(){
        $items = DB::table('food_items')->select('food_items.*')->get();
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
        $items = DB::table('food_items')->select('food_items.*')->where('food_items.id',$id)->first();
    // dd($items);
        return view('admin.foodItem.editItem',compact('items'));
    }

    public function updateFoodItem(Request $request){
         // dd($request);
         $this->validate($request,[
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
