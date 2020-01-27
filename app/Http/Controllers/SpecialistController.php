<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Specialist;

class SpecialistController extends Controller
{
    public function index()
    {
        $specialists = Specialist::get();

        return json_encode($specialists);
    }

    public function show($id)
    {
        $specialist = Specialist::where('id', $id)->first();

        return json_encode($specialist);
    } 
}
