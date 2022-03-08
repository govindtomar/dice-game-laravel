<?php
namespace App\Http\Controllers\API\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CategoryRequest;
use GovindTomar\CrudGenerator\Helpers\CRUDHelper;
use App\Models\Category;
use DB;
use Auth;

class CategoryController extends ApiController
{
    public function index()
    {
        try{
            $categories = Category::paginate(20);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Category lists',
                'data' =>  $categories,
            ]);
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function store(CategoryRequest $request)
    {
        try{
            $category = new Category;
            $category->name  =  $request->name;
			$category->slug  =  $request->slug;
            $category->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Save Category',
                'data' =>  $category,
            ]);
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function show($id)
    {
        try{
            $category = Category::find($id);  
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Show Category',
                'data' =>  $category,
            ]);          
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function update(CategoryRequest $request)
    {
        try{
            $category =  Category::find($request->id);
            $category->name  =  $request->name;
			$category->slug  =  $request->slug;
            $category->save();
            
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Update Category',
                'data' =>  $category,
            ]);
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function changeStatus(Request $request){
        try{
        // return $request->all();
            // $rules = array(
            //     'id' => ['required'],
            //     'status' => ['required']
            // );

            // $validator = Validator::make($request->all(), $rules);

            // if ($validator->fails()) {
            //     return $this->respondValidationError('Fields Validation Failed.', $validator);
            // }

            $category = Category::find($request->id);
            $category->status = $request->status;
            $category->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Category update successfully',
                'data' =>  $category,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
    
    public function destroy(Request $request)
    {
        try{
            $category = Category::find($request->id)->delete();
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Delete Category',
                'data' =>  $category,
            ]);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }
    


}
