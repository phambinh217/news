<?php

namespace Phambinh\News;

use Phambinh\Cms\Support\Traits\Query;
use Phambinh\Cms\Support\Traits\Metable;
use Phambinh\Cms\Support\Traits\Model as PhambinhModel;
use Illuminate\Database\Eloquent\Model;
use Phambinh\Cms\Support\Traits\Thumbnail;
use Phambinh\Cms\Support\Traits\SEO;

class News extends Model implements Query
{
    use PhambinhModel, Thumbnail, SEO;
    
    protected $table = 'newses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'sub_content',
        'author_id',
        'status',
        'thumbnail',
        'created_at',
        'updated_at',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

     /**
     * Các tham số được phép truyền vào từ URL
     *
     * @var array
     */
    protected static $requestFilter = [
        'id' => '',
        'title' => '',
        'status' => 'in:pending,enable,disable',
        'time_status' => 'in:coming,enable,disable',
        'category_id' => 'integer',
        'orderby' => '',
        '_keyword' => '',
    ];

    /**
     * Giá trị mặc định của các tham số
     *
     * @var array
     */
    protected static $defaultOfQuery = [
        'status'        => 'enable',
        'orderby'       => 'updated_at.desc',
    ];

    protected static $statusAble = [
        ['slug' => 'disable', 'name' => 'Xóa tạm'],
        ['slug' => 'enable', 'name' => 'Công khai'],
    ];

    /**
     * Trạng thái mặc định
     * @var string
     */
    protected static $defaultStatus = 'enable';

    protected static $searchFields = [
        'newses.id',
        'newses.title',
    ];

    public function categories()
    {
        return $this->beLongsToMany('Phambinh\News\Category', 'news_to_category');
    }

    public function author()
    {
        return $this->beLongsTo('Phambinh\Cms\User');
    }

    public function scopeOfQuery($query, $args = [])
    {
        $args = $this->defaultParams($args);
        $query->baseQuery($args);

        if (! empty($args['status'])) {
            switch ($args['status']) {
                case 'enable':
                    $query->enable();
                    break;
                
                case 'disable':
                    $query->disable();
                    break;
            }
        }

        if (! empty($args['_keyword'])) {
            $query->search($args['_keyword']);
        }

        if (! empty($args['author_id'])) {
            $query->where('author_id', $args['author_id']);
        }

        if (! empty($args['title'])) {
            $query->where('title', $args['title']);
        }

        if (! empty($args['category_id'])) {
            $query->join('news_to_category', 'newses.id', '=', 'news_to_category.news_id');
            $query->where('news_to_category.category_id', $args['category_id']);
        }
    }

    public function isEnable()
    {
        return $this->status == 1;
    }

    public function isDisable()
    {
        return $this->status == 0;
    }

    public function getHtmlClassAttribute()
    {
        if ($this->status == '0') {
            return 'bg-danger';
        }

        return null;
    }

    public static function getStatusAble()
    {
        return self::$statusAble;
    }

    public static function getDefaultStatus()
    {
        return self::$defaultStatus;
    }

    public function scopeEnable($query)
    {
        return $query->where('status', '1');
    }

    public function scopeSearch($query, $keyword)
    {
        $keyword = str_keyword($keyword);
        foreach (self::$searchFields as $index => $field) {
            if ($index == 0) {
                $query->where($field, 'like', $keyword);
            } else {
                $query->orWhere($field, 'like', $keyword);
            }
        }
    }

    public function scopeDisable($query)
    {
        return $query->where('status', '0');
    }

    public function markAsEnable()
    {
        $this->where('id', $this->id)->update(['status' => '1']);
    }

    public function markAsDisable()
    {
        $this->where('id', $this->id)->update(['status' => '0']);
    }

    public function getSubContentAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        if (!empty($this->content)) {
            return str_limit(strip_tags($this->content), 150);
        }

        return null;
    }

    public function setStatusAttribute($value)
    {
        switch ($value) {
            case 'disable':
                $this->attributes['status'] = '0';
                break;

            case 'enable':
                $this->attributes['status'] = '1';
                break;
        }
    }

    public function getStatusSlugAttribute()
    {
        if (! is_null($this->status)) {
            return $this->getStatusAble()[$this->status]['slug'];
        }

        return $this->getDefaultStatus();
    }

    public function getStatusNameAttribute()
    {
        return $this->getStatusAble()[$this->status]['name'];
    }
}
