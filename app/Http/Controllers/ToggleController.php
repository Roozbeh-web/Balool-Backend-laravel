<?php

namespace App\Http\Controllers;

use App\Models\Toggle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToggleController extends Controller
{
    public function toggle(Request $request){
        $id = Auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'is_happy' => 'required|boolean'
        ]);

        if($validator->fails()){
            return Response([
                'message' => $validator->messages(),
            ]);
        }else{
            $toggle = Toggle::create([
                'user_id' => $id,
                'is_happy' => $request->is_happy
            ]);

            return Response([
                'message' => 'toggled successfully.',
                'toggle_info' => $toggle
            ]);
        }
    }
}
