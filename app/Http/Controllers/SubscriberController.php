<?php
namespace App\Http\Controllers;
use App\Mail\Subscribe;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class SubscriberController extends Controller
{
    public function subscribe(Request $request){
        Mail::to("enricoayen3@gmail.com")->send(new Subscribe("enricoayen3@gmail.com"));
        return new JsonResponse(
            [
                'success' => true,
                'message' => "Thank you for subscribing to our email, please check your inbox"
            ], 200
        );
    }
}
