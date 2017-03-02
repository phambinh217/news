<?php

namespace Phambinh\News;

use Illuminate\Database\Eloquent\Model;

class NewsToCategory extends Model
{
    protected $table = 'news_to_category';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'news_id',
        'category_id',
    ];
}
