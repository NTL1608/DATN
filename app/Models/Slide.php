<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    protected $table = 'slides';
    public $timestamps = true;

    protected $fillable = ['title', 'link', 'image', 'description', 'target', 'active', 'sort'];

    const TARGET = [
        '0' => 'Không',
        '_blank' => '_blank',
        '_self' => '_self',
        '_parent' => '_parent',
        '_top' => '_top',
    ];

    const ACTIVE = [
        1 => "Hiển thị",
        0 => "Bản nháp"
    ];

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
}
