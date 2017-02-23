<?php

namespace Packages\News;

use Illuminate\Database\Eloquent\Model;
use Packages\Cms\Support\Traits\Query;
use Packages\Cms\Support\Traits\Metable;
use Packages\Cms\Support\Traits\Model as PhambinhModel;
use Packages\Appearance\Support\Traits\NavigationMenu;
use Packages\Cms\Support\Traits\Thumbnail;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model implements Query
{
    use PhambinhModel, NavigationMenu, Thumbnail;

    protected $table = 'news_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'parent_id',
        'group',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    protected static $requestFilter = [
        'id'  => 'integer',
        'limit' => '',
        'offset' => '',
        'orderby' => '',
    ];

    protected static $defaultOfQuery = [
        'orderby'       => 'id.desc',
    ];

    public function newses()
    {
        return $this->beLongsToMany('Packages\News\News', 'news_to_category');
    }

    public function scopeOfQuery($query, $args = [])
    {
        $query->baseQuery($args);
    }

    public function scopeOfParentAble($query)
    {
        $query->where('id', '!=', $this->id)->where('parent_id', '!=', $this->id);
    }

    public function menuUrl()
    {
        return 'url';
    }

    public function menuTitle()
    {
        return $this->name;
    }
}
