<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Models\Ui\Product;
use Illuminate\Http\Request;
use File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('product_name','ASC')->paginate(10);
        return response()->json($product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_name'=>['required','string'],
            'image' => ['required','image','mimes:jpeg,png,jpg'], //dimensions:max_width=500,max_height=500
            'unitprice' => ['required','numeric','min:1'],
            'category_id' => ['required'],
        ]);
        // check file
        if($request->hasFile('image')){
            $image = $request->file('image');
            $desitate_path = 'images/product';
            $imageName = time().'.'.$request->image->getClientOriginalName(); // to get image name and convert to number;            $img_resize->move($desitate_path,$imageName); //to store image
            $image->move($desitate_path,$imageName);
        }

        $product = new Product;
        $product->product_name = $request->product_name;
        $product->unitprice = $request->unitprice;
        $product->category_id = $request->category_id;
        $product->image = $imageName;
        $product->save();
        if($product){
            return response()->json([
                'status'=>'success',
                'message'=>'A product has been created',
            ]);
        }else{
            return response()->json([
                'status'=>'fali',
                'message'=>'Can not add this product',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ui\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ui\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ui\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validate = $request->validate([
            'new_product_name'=>['required','string'],
            // 'image' => ['required','image','mimes:jpeg,png,jpg'], //dimensions:max_width=500,max_height=500
            'new_unitprice' => ['required','numeric'],
            'new_category_id' => ['required'],
        ]);
        // delete old image
        if($request->hasFile('new_image')){
            // to delete old image before update
            $image_path = public_path("\images\product\\").$product->image;
            if(File::exists($image_path)){
                File::delete($image_path);
                //one way: unlink($image_path);
            }
            $image = $request->file('new_image');
            $desitate_path = 'images/product';
            $NewimageName = time().'.'.$request->new_image->getClientOriginalName(); // to get image name and convert to number;
            $image->move($desitate_path,$NewimageName); //to store image
        }else{
            $NewimageName = $product->image;  //if we dont use img validate the input file can be blank so the default image is old image
        }
        $product->product_name = $request->new_product_name;
        $product->category_id = $request->new_category_id;
        $product->unitprice = $request->new_unitprice;
        $product->image = $NewimageName;
        $product->save();
        if($product){
            return response()->json([
                'status'=>'success',
                'message'=>'Product has been Updated!'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'messate'=>'Cant Updated!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ui\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // check file delete
        $image_path = public_path("\images\product\\").$product->image;
        // check and delete file in directory
        if(File::exists($image_path)){
            File::delete($image_path);
            //u can use this
            // unlink($image_path);
        }
        $product->delete();
        if($product){
            return  response()->json([
                'status'=>'success',
                'message'=>'A Product has been deleted'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Can delete this Product!'
            ]);
        }
    }
}
