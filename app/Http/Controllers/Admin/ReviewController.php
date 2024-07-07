<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status =null;
        $reviews = Review::orderBy('created_at', 'desc')->where('parent_id',0)->with('childrenReviews');
        if($request->status != null){
            $reviews = $reviews->where('status',$request->status);
        }
        if($request->name != null){
            $reviews = $reviews->where('name','like','%'.$request->name.'%');
        }
        $reviews =$reviews->paginate(15);
        return view('admin.content.review.index', compact('reviews'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        //
        $reviews = Review::with('childrenReviews')->find($id);
        return view('admin.content.review.create',compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $product = Product::findOrFail($request->product_id);
        $review = new Review;
        $review->parent_id = $request->parent_id;
        $review->product_id = $request->product_id;
        $review->user_id = Auth::user()->id;
        $review->name = Auth::user()->name;
        $review->comment = $request->comment;
        $review->status = 1;
        $review->viewed = '0';
        $review->save();
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        return back();
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

    public function update_status(Request $request)
    {
        $review = Review::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        $product = Product::findOrFail($review->product->id);
        if(Review::where('product_id', $product->id)->where('status', 1)->count() > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/Review::where('product_id', $product->id)->where('status', 1)->count();
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        return response()->json([
            'result'=>true
        ]);
    }
}
