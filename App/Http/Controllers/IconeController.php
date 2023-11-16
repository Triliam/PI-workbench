<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Icone;

class IconeController extends Controller
{

    public function __construct(Icone $icone) {
        $this->icone = $icone;
    }

    public function index() {
        // return Icone::all();

        return response()->json($this->icone->all(), 200);
    }

    public function store(Request $request) {
        $icone = $this->icone->create([
           
            'icone' => $request->icone
        ]);
        return response()->json($icone, 201);
    }

    public function update(Request $request, $id) {
        $icone = $this->icone->find($id);
        if($icone === null) {
            return response()->json(['erro' => 'n existe'], 404);
        }

        // $request->validate($this->icone->rules(), $this->icone->feedback());

        $icone->update($request->all());
        return response()->json($icone, 200);
    }
}
