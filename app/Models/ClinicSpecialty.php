<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicSpecialty extends Model
{
    use HasFactory;

    protected $table = 'clinic_specialty';

    protected $fillable = ['clinic_id', 'specialty_id'];
}