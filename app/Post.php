<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    /**
     * Атрибуты, доступные для назначения
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'desc_short', 'desc', 'image', 'image_show', 'meta_title',
        'meta_desc', 'meta_key', 'published', 'created_by', 'edit_by'
    ];

    /**
     * Генерирует и устанавливает уникальный префикс для url категории
     */
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = Str::slug(mb_substr($this->title, 0, 40) . '-' . Carbon::now()->format('dmyHi'));
        $this->attributes['created_by'] = Auth::id();
    }

    /**
     * Полиморфная связь постов с категориями
     */
    public function categories()
    {
        return $this->morphToMany('App\Category', 'lk_post_to_category');
    }

    /**
     * Scope - отображать только опубликованные посты
     *
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    /**
     * Scope - сортировка по дате, с конца, и только последние $count записей
     *
     * @param $query
     * @param $count
     * @return mixed
     */
    public function scopeLastPosts($query, $count)
    {
        return $query->orderBy('created_at', 'desc')->take($count)->get();
    }



}
