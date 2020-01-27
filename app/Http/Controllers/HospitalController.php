<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hospital;
use App\Specialist;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::get();

        return json_encode($hospitals);
    }

    public function names()
    {
        $hospitals = Hospital::pluck('name')->toArray();

        return json_encode($hospitals);
    }

    public function show($id)
    {
        $hospital = Hospital::where('id', $id)->first();

        return json_encode($hospital);
    }

    public function showSpecialists($id)
    {
        $hospital = Hospital::where('id', $id)->first();

        return json_encode($hospital->specialists);
    }

    public function showSpecialist($hospital_id, $id)
    {
        $specialist = Specialist::where('hospital_id', $hospital_id)->where('id', $id)->first();

        return json_encode($specialist);
    }
}
