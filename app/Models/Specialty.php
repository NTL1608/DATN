<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    protected $table = 'specialties';
    protected $fillable = ['name', 'image', 'description', 'price', 'created_at', 'updated_at'];
    public $timestamps = true;

    /**
     * @param $request
     * @param string $id
     */
    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['_token', 'image', 'submit']);
        if ($request->image) {
            $image = upload_image('image');
            if ($image['code'] == 1) {
                $params['image'] = $image['name'];
            }
        }

        if ($id) {
            $slide = $this->find($id);
            return $slide->fill($params)->save();
        } else {
            return $this->fill($params)->save();
        }
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_specialty');
    }
}
