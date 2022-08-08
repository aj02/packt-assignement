<?php
namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Player;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class FileController extends Controller
{
      public function index(){
        return view('welcome');
      }

      public function uploadRecord(Request $request)
      {

        // $validated_data = $request->validate([
        //     'data' => ['required', 'image', 'max:2500'],
        //     'description' => ['required']
        // ]);

        $data = $request->input('data');
        $processed = false;

        try {
            DB::beginTransaction();
            FileUpload::create([
                'name' => $data['email'],
                'email'  => $data['email'],
                'person_prefix' => $data['person_prefix'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'active' => $data['active']
            ]);
            //Send Email
            // $this->sendEmail($email, $name);
            DB::commit();
            $processed = true;
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
        }

        if ($processed) {
            return response()->json([
                'message' => "Record successfully uploaded"
            ]);
        } else {
            //no file was uploaded
            throw new \Exception('No record was uploaded', Response::HTTP_BAD_REQUEST);
        }
   }

}
