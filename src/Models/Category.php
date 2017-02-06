<?php

namespace App\Modules\News\Src\Models;

use App\Modules\Post\Src\Models\Term;
use Illuminate\Database\Eloquent\Builder;

class Category extends Term
{
    protected $table = 'terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'slug',
        'parent_id',
        'group',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    /**
     * The database table meta used by the model.
     *
     * @var string
     */
    protected $metaTable = 'term_meta';

    /**
     * The foreign key name for the meta table
     *
     * @var string
     */
    protected $metaKeyName = 'term_id';

    /**
     * The attributes table meta
     *
     * @var array
     */
    protected $fillableMeta = [
        'description',
        'thumbnail',
        'icon',
    ];

    protected $requestFilter = [
        '_querySearch' => ['true', 'false'],
         'id',
        'orderby',
        'limit',
        'offset',
    ];

    protected $defaultOfQuery = [
        '_querySearch'  => 'false',
        'orderby'       => 'id.desc',
    ];

    public function newses()
    {
        return $this->beLongsToMany('App\Modules\News\Src\Models\News', 'news_to_category');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('group', function (Builder $builder) {
            $builder->where('group', 'news-category');
        });
    }

    /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function getThumbnail()
    {
        if (isset($this->toArray()['meta_data']['thumbnail']) && ! empty($this->toArray()['meta_data']['thumbnail'])) {
            return $this->toArray()['meta_data']['thumbnail'];
        }

        return website('thumbnail-default');
    }

    
    public function thumbnailOrDefault()
    {
        if (! empty($this->thumbnail)) {
            return $this->thumbnail;
        }

        return website('default-course-thumbnail');
    }
}
