<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $table = 'clinics';
    protected $fillable = ['name', 'email', 'phone', 'logo', 'address', 'link_website', 'description', 'contents', 'created_at', 'updated_at'];
    public $timestamps = true;

    /**
     * @param $request
     * @param string $id
     */
    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['_token', 'image', 'submit', 'specialties']);
        if ($request->image) {
            $image = upload_image('image');
            if ($image['code'] == 1) {
                $params['logo'] = $image['name'];
            }
        }

        if ($id) {
            $clinic = $this->find($id);
            $result = $clinic->fill($params)->save();

            // Cập nhật specialties
            if ($request->has('specialties')) {
                $clinic->specialties()->sync($request->specialties);
            }

            return $result;
        } else {
            $result = $this->fill($params)->save();

            // Gắn specialties nếu có
            if ($result && $request->has('specialties')) {
                $this->specialties()->sync($request->specialties);
            }

            return $result;
        }
    }

    /**
     * Mối quan hệ nhiều-nhiều với Specialty.
     */
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'clinic_specialty');
    }
}
