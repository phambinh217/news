<?php

namespace Phambinh\News;

use Illuminate\Database\Eloquent\Model;
use Phambinh\Cms\Support\Traits\Query;
use Phambinh\Cms\Support\Traits\Metable;
use Phambinh\Cms\Support\Traits\Model as PhambinhModel;
use Phambinh\Appearance\Support\Traits\NavigationMenu;
use Phambinh\Cms\Support\Traits\Thumbnail;
use Illuminate\Database\Eloquent\Builder;
use Phambinh\Cms\Support\Traits\SEO;

class Category extends Model implements Query
{
    use PhambinhModel, NavigationMenu, Thumbnail, SEO;

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
        'created_at',
        'updated_at',
    ];

    protected static $requestFilter = [
        'id'  => 'integer',
        'limit' => '',
        'offset' => '',
        'orderby' => '',
    ];

    protected static $defaultOfQuery = [
        'orderby'       => 'updated_at.desc',
    ];

    public function newses()
    {
        return $this->beLongsToMany('Phambinh\News\News', 'news_to_category');
    }

    public function scopeOfQuery($query, $args = [])
    {
        $args = $this->defaultParams($args);
        $query->baseQuery($args);
    }

    public function scopeOfParentAble($query)
    {
        $query->where('id', '!=', $this->id)->where('parent_id', '!=', $this->id);
    }

    public function getMenuUrlAttribute()
    {
        if (\Route::has('news.category')) {
            return route('news.category', ['slug' => $this->slug, 'id' => $this->id]);
        }
        return url($this->slug);
    }

    public function getMenuTitleAttribute()
    {
        return $this->name;
    }
}
