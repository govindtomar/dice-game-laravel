<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
	use SoftDeletes;
	
	protected $table = 'tasks';
	
    protected $fillable = ['name','text',];

    public function categories(){
        return $this->belongsToMany(Category::class, 'task_category', 'task_id', 'category_id');
    }

    
}