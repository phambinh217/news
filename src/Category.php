<?php

namespace Phambinh\News;

use Illuminate\Database\Eloquent\Model;
use Phambinh\Laravel\Database\Traits\Query;
use Phambinh\Laravel\Database\Traits\Metable;
use Phambinh\Laravel\Database\Traits\Model as PhambinhModel;
use Phambinh\Appearance\Support\Traits\NavigationMenu;
use Phambinh\Cms\Support\Traits\Thumbnail;
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
        return $this->beLongsToMany('Phambinh\News\News', 'news_to_category');
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
