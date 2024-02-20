<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventImagesController extends Controller
{
    public $tabla;
    public function __construct()
    {
        $this->tabla = DB::table("event_images");
    }

    public function index()
    {
        $data = $this->tabla->get();
        return response()->json($data, 200);
    }

    public function show($id)
    {
        $data = $this->tabla->find($id);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'file' => 'required|file|mimes:jpg,jpeg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $id = $request->input('event_id');
            $mode = $request->input('mode');
            $message = $request->input('message');

            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $carpeta = "images";
            $publicPath = base_path("/public/files/" . $id . "/" . $carpeta);
            $file->move($publicPath, $fileName);

            $data = ["path" => $fileName, "message" => $message, "mode" => $mode, "event_id" => $id];

            DB::table('event_images')->insert($data);

            return response()->json(['message' => 'Archivo subido correctamente', 'file_name' => $fileName], 200);
        } else {
            return response()->json(['message' => 'No se proporcionó ningún archivo'], 400);
        }
    }

    public function update($id, Request $request)
    {
        $json = $request->all();
        $data = $this->tabla
            ->where('id', $id)
            ->update($json);

        $response = [
            "data" => $data,
            "message" => "Se actualizo correctamente el registro!"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $data = $this->tabla->where('id', $id)->delete();

        $response = [
            "data" => $data,
            "message" => "Se elimino correctamente el registro",
        ];
        return response()->json($response, 200);
    }
}
