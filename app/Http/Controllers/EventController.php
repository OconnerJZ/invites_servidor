<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public $tabla;
    public function __construct()
    {
        $this->tabla = DB::table("event");
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

    public function invite($id, $guest)
    {
        $event = $this->tabla
            ->select("id", "name_celebrated as nombreFestejado", "phrase as frase", DB::raw("CONCAT_WS(' ',event_day,event_time) as dateBirthday"), "avatar", "song")
            ->find($id);
        $ceremony = DB::table("event_locations")
            ->where("type_event", 1)
            ->where("event_id", $id)
            ->select("event_id", "name_location as nameOficial", "name_place as namePlace", "time_event as hourEvent", "name_maps as nameMaps", "lat", "lon")
            ->get()->first();
        $location = DB::table("event_locations")
            ->where("type_event", 2)
            ->where("event_id", $id)
            ->select("event_id", "name_location as nameOficial", "name_place as namePlace", "time_event as hourEvent", "name_maps as nameMaps", "lat", "lon")
            ->get()->first();
        $dresscode = DB::table("event_dresscode")
            ->where("event_id", $id)
            ->get()->first();
        $images = DB::table("event_images")
            ->where("event_id", $id)
            ->where("mode", "image")
            ->get(["id", "path", "message"]);
        $bg = DB::table("event_images")
            ->where("event_id", $id)
            ->whereNot("mode", "image")
            ->get();

        $bgs = $this->getFormatImages($bg);
        $ceremony->image = $bgs['ceremony']['path'];
        $location->image = $bgs['location']['path'];

        if ($guest != "undefined") {
            $guests = DB::table("event_guest")
                ->where("event_id", $id)
                ->select("name", "family", "guests", DB::raw("IF(no_table > 9, LPAD(no_table, 2, '0'), LPAD(no_table, 3, '0') ) as noTable"), "confirmation")
                ->find($guest);
            $guests->confirmation = $guests->confirmation == 1 ? true : false;
            $guests->table = $this->extractFirstCharacters($event->nombreFestejado) . " " . $guests->noTable;
        } else {
            $guests = new \stdClass();
            $guests->confirmation = false;
            $guests->table = $this->extractFirstCharacters($event->nombreFestejado) . " XXX";
            $guests->family = "XXX XXX";
            $guests->guests = "X";
            $guests->noTable = "XXX";
        }

        $guests->name = explode(" ", $event->nombreFestejado)[0];
        $guests->nameParty = $this->extractFirstCharacters($event->nombreFestejado);
        $guests->hourCeremony = isset($ceremony->hourEvent) ? $ceremony->hourEvent : "";
        $guests->hourReception = isset($location->hourEvent) ? $location->hourEvent : "";
        $guests->date = explode(" ", $event->dateBirthday)[0];
        $guests->link = "https://youtu.be/QJc78BLFKQI?si=gpU1CcO4lid3cDzd";

        $invite = [
            "event_id" => $id,
            "hasCounterDown" => true,
            "birthday" => $event,
            "ceremony" => empty($ceremony) ? (object) [] : $ceremony,
            "location" => empty($location) ? (object) [] : $location,
            "dressCode" => empty($dresscode) ? (object) [] : $dresscode,
            "ticket" => $guests,
            "images" => $images,
            "bg" => $bgs
        ];

        return response()->json($invite, 200);
    }

    public function extractFirstCharacters($phrase)
    {
        $words = explode(" ", $phrase);
        $characters = "";
        foreach ($words as $word) {
            $characters .= $word[0];
        }
        return $characters;
    }

    public function getFormatImages($images)
    {
        $img = [];
        foreach ($images as $json) {
            $mode = $json->mode;
            $path = $json->path;
            $message = $json->message;

            if (!isset($img[$mode])) {
                $img[$mode] = [];
            }
            $img[$mode] = ['path' => $path, 'message' => $message];
        }
        return $img;
    }
}
