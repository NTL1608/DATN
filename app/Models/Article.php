<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $fillable = [
        'name',
        'slug',
        'view',
        'description',
        'image',
        'content',
        'category_id',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @param $request
     * @param string $id
     * @return mixed
     */
    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['images', '_token']);
        if (isset($request->images) && !empty($request->images)) {
            $image = upload_image('images');
            if ($image['code'] == 1)
                $params['image'] = $image['name'];
        }
        $params['slug'] = Str::slug($request->name);

        if ($id) {
            return $this->find($id)->update($params);
        }
        return $this->create($params);
    }
}
