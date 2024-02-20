<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        $rules = [
            'file' => 'required|file|mimes:jpg,jpeg,mp3',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $id = $request->input('id');
            $param = $request->input('param');

            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $carpeta = $file->getClientOriginalExtension() == "jpg" ? "images" : "audios";
            $publicPath = base_path("/public/files/".$id."/".$carpeta);
            $file->move($publicPath, $fileName);

            DB::table('event')
                ->where("id", $id)
                ->update([
                    $param => $fileName
                ]);

            return response()->json(['message' => 'Archivo subido correctamente', 'file_name' => $fileName], 200);
        } else {
            return response()->json(['message' => 'No se proporcionó ningún archivo'], 400);
        }
    }
}
