<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

//require 'vendor/autoload.php';
class firebaseController extends Controller
{
    public function index(){
        //ambil/read service account credential
        $serviceAccount= (new Factory)->withServiceAccount(__DIR__.'/freelancerops-firebase-adminsdk-bmmu2-8ecc49d11b.json');

        //membuat firestore berdasar data dari service accounr
        $firestore= $serviceAccount->createFirestore();

        //membuat database dari firestore
        $database= $firestore->database();

        //membuat document baru di collection pada database
        $testRef= $database->collection('testUser')->newDocument();

        //memasukan data document
        $testRef->set([
            'firstName'=> 'Test',
            'lastName'=> 'newUser',
            'Age'=>'20'
        ]);
    }

    public function info(){
        phpinfo();
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $image = $request->file('image'); //image file from frontend

        $student   = app('firebase.firestore')->database()->collection('Images')->document('defT5uT7SDu9K5RFtIdl');
        $firebase_storage_path = 'testFolder/';
        $name     = $student->id();
        $localfolder = public_path('firebase-temp-uploads') .'/';
        $extension = $request->file('image')->getClientOriginalExtension();
        $file      = $name. '.' . $extension;
        if ($image->move($localfolder, $file)) {
          $uploadedfile = fopen($localfolder.$file, 'r');
          app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
          //will remove from local laravel folder
          unlink($localfolder . $file);
        }
        return back()->withInput();
    }
}
