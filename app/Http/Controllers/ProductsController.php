<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Products;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datas = Products::all();

        return response()->json(["message" => "Get All Data Products","status"=>200, "data" => $datas], 200);
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
        //
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,svg,gif|max:2048',
        ]);

        $product = new Products;
        $product -> name_product = $request->get('name_product');
        $product -> thumbnail = $request->get('thumbnail');
        $product -> category = $request->get('category');
        $product -> weight = $request->get('weight');
        $product -> price = $request->get('price');
        $product -> stock = $request->get('stock');
        $product -> description = $request->get('description');

        if($request->hasfile('img'))
        {
            $file = $request -> file('img');
            $dest = 'uploads/img_products'.'/';
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move($dest, $filename);

            $product ->img = $filename;
        }
        $product->save();

        return response()->json(["message"=>"Products Successfully Created", "status"=>201, compact('product')], 201);

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
        $datas = Products::find($id);

        return response()->json(["messages"=>"Get Products By Id","status"=>200, "data"=>$datas], 200);
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
        //
        $product = Products::find($id);

        $product -> name_product = $request->name_product;
        $product -> thumbnail = $request->thumbnail;
        $product -> category = $request->category;
        $product -> weight = $request->weight;
        $product -> price = $request->price;
        $product -> stock = $request->stock;
        $product -> description = $request->description;

        if($request->hasfile('img'))
        {
            $destination = 'uploads/img_products/'.$product->img;
            if(File::exists($destination))
            {
                File::delete($destination);
            }
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file -> move('uploads/img_products/', $filename);
            $product -> img = $filename; 
        }
        $product->update();
        return response()->json(["messages"=>"Products Successfully Updated", "status"=>200, compact('product')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Products::find($id);
        $destination = 'uploads/img_products/'.$product->img;
        if(File::exists($destination))
        {
            File::delete($destination);
        }
        $product -> delete();
        return response()->json(["messages"=>"Deleted Products Successfully", "status"=>200], 200);
    }
}
