<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;

use App\Models\Ui\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $table = Table::orderBy('name','ASC')->paginate(10);
        return response()->json($table);
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
            'name'=>['required','string','unique:tables,name']
        ]);
        $table = new Table;
        $table->name = $request->name;
        $table->save();
        if($table){
            return response()->json([
                'status'=>'success',
                'message'=>'A table has been created',
            ]);
        }else{
            return response()->json([
                'status'=>'fali',
                'message'=>'Can not add this table',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        $validate = $request->validate([
            'name'=>['required','string','unique:tables,name,'.$table->id]
        ]);
        
        $table->name = $request->name;
        $table->save();
        if($table){
            return response()->json([
                'status'=>'success',
                'message'=>'A table has been updated',
            ]);
        }else{
            return response()->json([
                'status'=>'fali',
                'message'=>'Can not update this table',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        $table->delete();
        if($table){
            return  response()->json([
                'status'=>'success',
                'message'=>'A table has been deleted'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Can delete this table!'
            ]);
        }
    }
}
