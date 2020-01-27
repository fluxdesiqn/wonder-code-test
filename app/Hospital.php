<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Specialist;

class Hospital extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hospital';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'location', 'type', 'href', 'logo', 'xml'
    ];

    public function specialists()
    {
        return $this->hasMany(Specialist::class, 'hospital_id');
    }
}
