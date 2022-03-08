<?php
namespace App\Http\Controllers\API\V1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\TaskRequest;
use GovindTomar\CrudGenerator\Helpers\CRUDHelper;
use App\Models\Task;
use App\Models\Category;
use DB;
use Auth;

class TaskController extends ApiController
{
    public function index()
    {
        try{
            $tasks = Task::with('categories')->paginate(20);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Task lists',
                'data' =>  $tasks,
            ]);
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function store(TaskRequest $request)
    {
        try{
            $task = new Task;
            $task->task  =  $request->task;
			$task->detail  =  $request->detail;
            $task->save();

            foreach ($request->task_categories as $key => $task_category) {
                $category = Category::where('name', $task_category)->first();
                $task->categories()->attach($category->id);
            }

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Save Task',
                'data' =>  $task,
            ]);
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function show($id)
    {
        try{
            $task = Task::with('categories')->find($id);  
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Show Task',
                'data' =>  $task,
            ]);          
        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

    public function update(TaskRequest $request)
    {
        try{
            $task =  Task::find($request->id);
            $task->task  =  $request->task;
			$task->detail  =  $request->detail;
            $task->save();
            
            $categories = [];
            foreach ($request->task_categories as $key => $task_category) {
                $categories[] = Category::where('name', $task_category)->first()->id;
            }

            $task->categories()->sync($categories);

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Update Task',
                'data' =>  $task,
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

            $task = Task::find($request->id);
            $task->status = $request->status;
            $task->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Category update successfully',
                'data' =>  $task,
            ]);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try{
            $task = Task::find($request->id)->delete();
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Delete Task',
                'data' =>  $task,
            ]);

        }catch(\Exception $e){
            return $this->respondWithError($e->getMessage());
        }
    }

}
