<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventLocationsController extends Controller
{
    public $tabla;
    public function __construct()
    {
        $this->tabla = DB::table("event_locations");
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
        $data = $request->json()->all();
        try {
            $this->tabla->insert($data);
            return response()->json(['message' => 'Registro insertado correctamente'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al insertar el registro', 'error' => $e->getMessage()], 500);
        }
    }
    public function update($id, Request $request)
    {
        $json = $request->json()->all();
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
