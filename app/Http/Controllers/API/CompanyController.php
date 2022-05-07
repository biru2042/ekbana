<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\EditCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\CompanyRequest;
use App\Http\Controllers\Controller; 
use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use App\Models\Company;
use Carbon\Carbon;
use Response;
use Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return CompanyResource::collection(Company::with('category')
                    ->paginate(10)); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(CompanyRequest $request)
    {
        try{
            
            $company = new Company;

            $profileImage = '';
            if ($image = $request->file('image')) {
                $destinationPath = public_path('image/');
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                
            }
            
            $company->title = $request->title;
            $company->image = $profileImage;
            $company->description = $request->description;
            $company->status = $request->status;
            $company->created_at = Carbon::now();
            if($request->category_id){
               $category = CompanyCategory::find($request->category_id); 
               $category = $category->companies()->save($company);
            }
            $company->save();

            return Response::json(array('success'=>'Created'));
            
        }catch(\Excecption $e){
          return Response::json(array('error'=>'Something went wrong. Please try again.')); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Request $company, $id)
    {
        return new CompanyResource(Company::where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(EditCompanyRequest $request, Company $company)
    {
        try{
            if ($image = $request->file('image')) {
                $imagePath = str_replace('\\', '/', public_path('image/'.$company->image));
                if (\File::exists($imagePath)) {
                    
                    unlink($imagePath);
                }
                
                $destinationPath = public_path('image/');
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $company->image = $profileImage;
            }

            $company->title = $request->title;
            $company->category_id = $request->category_id;
            
            $company->description = $request->description;
            $company->status = $request->status ?? 0;
            $company->updated_at = Carbon::now();
            $company->save();
            return Response::json(array('success'=>'Updated'));
        }catch(\Excecption $e){
            return Response::json(array('error'=>'Something went wrong. Please try again.'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try{
            $imagePath = str_replace('\\', '/', public_path('image/'.$company->image));
            if (\File::exists($imagePath)) {
                
                unlink($imagePath);
            }
           $company->delete();
           return Response::json(array('success'=>'deleted'));
        }catch(\Exception $e){
            return Response::json(array('error'=>'Something went wrong. Please try again.'));
        }
    }
}
