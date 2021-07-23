<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{

    /**
     * Return the taged list of categories
     * @return string
     */
    public static function getCategoryList()
    {

        $res = '<div class="tags">';
        $categories = self::get();

        foreach ($categories as $category) {
            $res .= '<a href="#" <i class="' . trans('authorization/category.' . $category->icon) . '"></i>' . trans('authorization/category.' . $category->category_name) . '</a>';
        }
        $res .= '</div>';
        return $res;
    }
}
