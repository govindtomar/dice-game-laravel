<?php
namespace App\Http\Controllers\API\V1\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\Category;

class TaskController extends ApiController
{

    public function show()
    {
        try{
            $task = Task::first();  
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


}
