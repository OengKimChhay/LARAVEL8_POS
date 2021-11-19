<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Models\Ui\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('category_name','ASC')->withCount('products')->paginate(10);
        return response()->json($category);
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
            'category_name'=>['required','max:30','string','unique:categories,category_name'],
        ],[
            'category_name.unique' =>'This category name is already exist'
        ]);
        $category = Category::create($request->all());
        if($category){
            return response()->json([
                'status' =>'success',
                'message'=>'A category has been added!'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Cant add this category!'
            ]);
        }
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
        //
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
        $category = Category::find($id);
        $validate = $request->validate([
            'category_name'=>['required','max:30','string','unique:categories,category_name,'.$category->id],
        ],[
            'category_name.unique' =>'This category name is already exist'
        ]);

        $category->category_name = $request->category_name;
        $category->save();
        if($category){
            return response()->json([
                'status' =>'success',
                'message'=>'A category has been Update!'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Cant update this category!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        if($category){
            return response()->json([
                'status' =>'success',
                'message'=>'A category has been deleted!'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Cant delete this category!'
            ]);
        }
    }
}
