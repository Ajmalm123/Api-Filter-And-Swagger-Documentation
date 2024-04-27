<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CheckResponseController extends Controller
{
    public function __invoke(){
        // return apiful()->success();
        // $data=Post::all();
        // return apiful($data)->withMessage("This is message")->success();
          // Or
//   return apiful()->withData($data)->withMessage("This is message")->success();

try {
    $user=new User();
    $user->email='gibson.rowena@example.org';
    $user->save();
  
    // return apiful()->success();
  
  }catch (\Exception $e){
    return apiful()->badRequest();

    
    // return apiful()->exception($e);
    // Or
    // return apiful($e);
  
  }

    }
}
