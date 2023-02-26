<?php

use think\Model;

class DemoModel extends Model
{
    protected $name = 'users';
    use \DeathSatan\ThinkOrmUtils\FastQueryTrait;

    public function tt(){
        return $this->hasOne(TTModel::class,'id','app_id');
    }
}