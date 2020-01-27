<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Hospital;

class Specialist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'specialist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'src', 'title', 'hospital_id'
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
