<?php

namespace App\Modules\News\Src\Models;

use App\Modules\Post\Src\Models\Post;

class News extends Post
{
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
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

     /**
     * The database table meta used by the model.
     *
     * @var string
     */
    protected $metaTable = 'news_meta';

    /**
     * The foreign key name for the meta table
     *
     * @var string
     */
    protected $metaKeyName = 'news_id';

    /**
     * The attributes table meta
     *
     * @var array
     */
    protected $fillableMeta = [
        'thumbnail',
    ];

     /**
     * Các tham số được phép truyền vào từ URL
     *
     * @var array
     */
    protected $requestFilter = [
        'id',
        'orderby',
        'title',
        'status'        => [ 'pending', 'enable', 'disable' ],
        'time_status'   =>  ['coming', 'learning', 'finished'],
        'category_id',
        '_keyword',
    ];

    /**
     * Giá trị mặc định của các tham số
     *
     * @var array
     */
    protected $defaultOfQuery = [
        'status'        => 'enable',
        'orderby'        =>    'created_at.desc',
    ];

    public function categories()
    {
        return $this->beLongsToMany('App\Modules\News\Src\Models\Category', 'news_to_category');
    }

    public function author()
    {
        return $this->beLongsTo('App\Modules\User\Src\Models\User');
    }

    /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function scopeOfQuery($query, $args = [])
    {
        $args = $this->defaultOfQuery($args);
        
        $this->baseQuery($query, $args);

        if (! empty($args['status'])) {
            switch ($args['status']) {
                case 'all':
                    break;

                case 'enable':
                    $query->where('newses.status', '1');
                    break;
                
                case 'disable':
                    $query->where('newses.status', '0');
                    break;
            }
        }

        if (! empty($args['_keyword'])) {
            $this->querySearch($query, $args['_keyword'], [
                'newses.id',
                'newses.title',
            ]);
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

     /**
     *
     * Kiểm tra tin tức là enable
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function isEnable()
    {
        $statusCode = $this->status;
        return $statusCode == '1';
    }

    /**
     *
     * Kiểm tra tin tức là Disable
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function isDisable()
    {
        $statusCode = $this->status;
        return $statusCode == '0';
    }

     /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function statusHtmlClass()
    {
        if ($this->status == '0') {
            return 'bg-danger';
        }

        return null;
    }

    /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function thumbnailOrDefault()
    {
        if (! empty($this->thumbnail)) {
            return $this->thumbnail;
        }

        return website('default-news-thumbnail');
    }
}
