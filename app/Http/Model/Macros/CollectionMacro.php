<?php
namespace App\Http\Model\Macros;

use Illuminate\Database\Eloquent\Collection;

class CollectionMacro
{

    public function pluckDistant()
    {
        return function ($relationship, $value, $key = null) {
            return $this->map(function($item) use($relationship, $value, $key) {
                $relation = $item->getRelation($relationship);
    
                if (get_class($relation) == \Illuminate\Support\Collection::class ||
                    get_class($relation) == \Illuminate\Database\Eloquent\Collection::class) {
                    $item->setRelation($relationship, $relation->pluck($value, $key));
                }
    
                return $item;
            });
        };
    }

    public function defaultDropDown()
    {
        return function () {
            $collection = new Collection();
            $collection = $this->map(function ($v) {
                return (object)[
                    'id'   => $v->id,
                    'code' => $v->code,
                    'name' => $v->name,
                    'name_en' => $v->name_en,
                ];
            }); 
            return $collection;
        };
    }

    public function accountedAutoList()
    {
        return function () {
            $collection = new Collection();
            $collection = $this->map(function ($v) {
                return (object)[
                    'id'   => $v->id,
                    'code' => $v->code,
                    'name' => $v->name,
                ];
            }); 
            return $collection;
        };
    }
}