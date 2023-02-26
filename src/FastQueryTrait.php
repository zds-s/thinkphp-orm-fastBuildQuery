<?php

namespace DeathSatan\ThinkOrmUtils;

use DeathSatan\ThinkOrmUtils\Enums\WhereEnum;
use think\db\Query;
use think\Model;

trait FastQueryTrait
{
    public static function buildQuery(array $fields,array $params,array $withQuery = [])
    {
        $query = new static;
        if (count($withQuery) != 0){
            $withParams = [];
            foreach ($withQuery as $with => $withWhere){
                $withFunc = function (Query $query)use ($withWhere,$params){
                    if (is_array($withWhere)){
                        foreach ($withWhere as $key => $whereEnum){
                            $asField = stripos($key,' as ');
                            $asFieldExist = $asField !== false;
                            $logic = 'AND';
                            if ($asFieldExist){
                                $oldField = substr($key,0,$asField);
                                $newField = substr($key,$asField+4);
                            }
                            if (is_array($whereEnum) && count($whereEnum) == 2){
                                $logic = $whereEnum[1];
                                $whereEnum = $whereEnum[0];
                            }
                            if ($asFieldExist){
                                $paramsKey = $newField;
                            }
                            else{
                                if (strstr($key, '.')) {
                                    $paramsKey = substr($key, strrpos($key, '.') + 1);
                                }
                                else {
                                    $paramsKey = $key;
                                }
                            }

                            if (empty($params[$paramsKey])){
                                continue;
                            }
                            $value = $params[$paramsKey];
                            if ($asFieldExist){
                                $key = $oldField;
                            }
                            $query = static::parseWhere($whereEnum,$key,$value,$query,$logic);
                        }
                    }
                };
                $withParams[$with] = $withFunc;
            }
            $query = $query->with($withParams);
        }
        foreach ($fields as $field => $op){
            $asField = stripos($field,' as ');
            $asFieldExist = $asField !== false;
            if (is_array($op) && count($op) != 2){
                $logic = $op[1];
                $op = $op[0];
            }else{
                $logic = 'AND';
            }
            if ($asFieldExist){
                $oldField = substr($field,0,$asField);
                $newField = substr($field,$asField+4);
            }

            // 如果有.则分割出来
            if ($asFieldExist){
                $paramsKey = $newField;
            }else {
                if (strstr($field, '.')) {
                    $paramsKey = substr($field, strrpos($field, '.') + 1);
                } else {
                    $paramsKey = $field;
                }
            }
            if (empty($params[$paramsKey])){
                continue;
            }
            $value = $params[$paramsKey];

            if ( !($op instanceof WhereEnum)){
                throw new \RuntimeException("条件错误，请传入\\DeathSatan\\ThinkOrmUtils\\Enums\\WhereEnum枚举属性");
            }
            if ($asFieldExist){
                $field = $oldField;
            }
            $query = self::parseWhere($op,$field,$value,$query,$logic);
        }
        return $query;
    }

    public static function parseWhere(WhereEnum $whereEnum,string $field,mixed $value,Model|Query $model,string $logic = 'AND'){
        switch ($whereEnum){
            case WhereEnum::LIKE:
                if (is_array($value)){
                    foreach ($value as $val){
                        $model = $model->whereLike($field,'%'.$val.'%');
                    }
                }else{
                    $model = $model->whereLike($field,'%'.$value.'%',$logic);
                }
                break;
            case WhereEnum::LIKE_LEFT:
                if (is_array($value)){
                    foreach ($value as $val){
                        $model = $model->whereLike($field,'%'.$val,$logic);
                    }
                }else{
                    $model = $model->whereLike($field,'%'.$value,$logic);
                }
                break;
            case WhereEnum::LIKE_RIGHT:
                if (is_array($value)){
                    foreach ($value as $val){
                        $model = $model->whereLike($field,$val.'%',$logic);
                    }
                }else{
                    $model = $model->whereLike($field,$value.'%',$logic);
                }
                break;
            case WhereEnum::NOT_LIKE:
                if (is_array($value)){
                    foreach ($value as $val){
                        $model = $model->whereNotLike($field,'%'.$val.'%',$logic);
                    }
                }else{
                    $model = $model->whereNotLike($field,'%'.$value.'%',$logic);
                }
                break;
            case WhereEnum::NOT_LIKE_LEFT:
                if (is_array($value)){
                    foreach ($value as $val){
                        $model = $model->whereNotLike($field,'%'.$val,$logic);
                    }
                }else{
                    $model = $model->whereNotLike($field,'%'.$value,$logic);
                }
                break;
            case WhereEnum::NOT_LIKE_RIGHT:
                if (is_array($value)){
                    foreach ($value as $val){
                        $model = $model->whereNotLike($field,$val.'%',$logic);
                    }
                }else{
                    $model = $model->whereNotLike($field,$value.'%',$logic);
                }
                break;
            case WhereEnum::EQ:
                if (is_array($value)){
                    throw new \RuntimeException(sprintf('字段%s,给定的值为数组',$field));
                }
                $model = $model->where($field,'=',$value,$logic);
                break;
            case WhereEnum::NE:
                if (is_array($value)){
                    throw new \RuntimeException(sprintf('字段%s,给定的值为数组',$field));
                }
                $model = $model->where($field,'<>',$value,$logic);
                break;
            case WhereEnum::LT:
                if (is_array($value)){
                    throw new \RuntimeException(sprintf('字段%s,给定的值为数组',$field));
                }
                $model = $model->where($field,'<',$value,$logic);
                break;
            case WhereEnum::GT:
                if (is_array($value)){
                    throw new \RuntimeException(sprintf('字段%s,给定的值为数组',$field));
                }
                $model = $model->where($field,'>',$value,$logic);
                break;
            case WhereEnum::GE:
                if (is_array($value)){
                    throw new \RuntimeException(sprintf('字段%s,给定的值为数组',$field));
                }
                $model = $model->where($field,'<=',$value,$logic);
                break;
            case WhereEnum::LE:
                if (is_array($value)){
                    throw new \RuntimeException(sprintf('字段%s,给定的值为数组',$field));
                }
                $model = $model->where($field,'>=',$value,$logic);
                break;
            case WhereEnum::BETWEEN:
                if (!is_array($value) || count($value)!=2){
                    throw new \RuntimeException(sprintf('字段%s,给定的数组长度不符合',$field));
                }
                $model = $model->whereBetween($field,$value,$logic);
                break;
            case WhereEnum::IN:
                $model = $model->whereIn($field,$value,$logic);
                break;
            case WhereEnum::NOT_BETWEEN:
                if (!is_array($value) || count($value)!=2){
                    throw new \RuntimeException(sprintf('字段%s,给定的数组长度不符合',$field));
                }
                $model = $model->whereNotBetween($field,$value,$logic);
                break;
            case WhereEnum::NOT_IN:
                $model = $model->whereNotIn($field,$value,$logic);
                break;
            case WhereEnum::NOT_NULL:
                $model = $model->whereNotNull($field,$logic);
                break;
            case WhereEnum::NULL:
                $model = $model->whereNull($field,$logic);
                break;
            case WhereEnum::FIND_IN_SET:
                $model = $model->whereFindInSet($field,$value,$logic);
                break;
        }
        return $model;
    }
}