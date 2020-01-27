<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialist;

class SpecialistController extends Controller
{
    public function index()
    {
        $specialists = Specialist::get();
        $specialistData = [];

        foreach($specialists as $specialist) {
            $specialistData[] = [
                'name' => $specialist->name,
                'title' => $specialist->title,
                'hospital' => $specialist->hospital->name
            ];
        }

        return json_encode($specialistData);
    }

    public function show($id)
    {
        $specialist = Specialist::where('id', $id)->first();

        return json_encode($specialist);
    } 
}
