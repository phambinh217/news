<?php

namespace Phambinh\News\Models;

use Illuminate\Database\Eloquent\Model;
use Phambinh\Laravel\Database\Traits\Query;
use Phambinh\Laravel\Database\Traits\Metable;
use Phambinh\Laravel\Database\Traits\Model as PhambinhModel;
use Phambinh\Appearance\Supports\Traits\NavigationMenu;

use Illuminate\Database\Eloquent\Builder;

class Category extends Model implements Query
{
    use PhambinhModel, NavigationMenu;

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
        return $this->beLongsToMany('Phambinh\News\Models\News', 'news_to_category');
    }
    
    public function thumbnailOrDefault()
    {
        if (! empty($this->thumbnail)) {
            return $this->thumbnail;
        }

        return setting('thumbnail-default');
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
