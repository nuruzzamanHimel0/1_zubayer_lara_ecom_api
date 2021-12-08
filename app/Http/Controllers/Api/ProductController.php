<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Image;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['category'])->latest()->get();
        if(isset($products)){
            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        }
        // dd($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return response()->json([
        //     'status' => 'success',
        //     'data' => $request->all()
        //    ]);

         $this->validate($request, [
            'product_name' => 'required',
            'category_id' => 'required',
            'product_short_description' => 'required',
            'product_long_description' => 'required',
           'product_price'=>'required|integer',
            'publication_status' => 'required',
            'product_image' => 'required',

        ]);

         // data:image/jpeg;base64,/9j/4SUqRXhpZgAASUkqAA
         // "data:image/jpeg"

         $strpos = strpos($request->product_image, ';');
         $string = substr($request->product_image, 0,$strpos);

         $extention = substr(strrchr($string, '/'), 1);

        $image_name = rand().".".$extention;
        $image_save = public_path('images/products/'.$image_name);

        // open an image file
        $img = Image::make($request->product_image);
        // now you are able to resize the instance
        $img->resize(320, 240);
        // finally we save the image as a new file
        $img->save($image_save);


        $insert_product =  Product::Create([
           'product_name'=>$request->product_name,
           'category_id'=>$request->category_id,
           'product_short_description'=>$request->product_short_description,
           'product_long_description'=>$request->product_long_description,
           'product_price'=>$request->product_price,
           'publication_status'=>$request->publication_status,
           "product_image" => $image_name,
           'created_at'=>Carbon::now(),
        ]);

          return response()->json([
            'status' => 'success',
            
           ]);


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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
}
