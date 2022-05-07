<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\EditCompanyCategoryRequest;
use App\Http\Resources\CompanyCategoryResource;
use App\Http\Requests\CompanyCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;

class CompanyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
       return CompanyCategoryResource::collection(CompanyCategory::where('title', 'LIKE', "%$request->title%")
                    ->orWhere('created_at', '=', date('Y-m-d'))
                    ->with('companies')
                    ->paginate(10)); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyCategoryRequest $request)
    {
        try{
            CompanyCategory::create([
                "title" => request()->title,
                "created_at" => Carbon::now()
            ]);
            return Response::json(array('success'=>'Created'));
        }catch(\Excecption $e){
            return Response::json(array('error'=>'Something went wrong. Please try again.')); 
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyCategory  $companyCategory
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request, $id)
    public function show(Request $request, $id)
    {
        
        return new CompanyCategoryResource(CompanyCategory::where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyCategory  $companyCategory
     * @return \Illuminate\Http\Response
     */
    public function update(EditCompanyCategoryRequest $request, CompanyCategory $companyCategory)
    {
        try{
            $companyCategory->title = $request->title;
            $companyCategory->updated_aat = Carbon::now();
            return Response::json(array('success'=>'Updated')); 
        }catch(\Excecption $e){
            return Response::json(array('error'=>'Something went wrong. Please try again.')); 
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyCategory  $companyCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyCategory $companyCategory)
    {
        try{
            $companyCategory->delete();
            return Response::json(array('success'=>'Deleted'),204); 
        }catch(\Excecption $e){
            return Response::json(array('error'=>'Something went wrong. Please try again.')); 
        }
    }
}
