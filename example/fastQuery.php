<?php

use DeathSatan\ThinkOrmUtils\Enums\WhereEnum;

require_once 'init.php';
$demoModel = new DemoModel();

$query = DemoModel::buildQuery(
    [
        'account as username'=> WhereEnum::LIKE,
        'avatar'=>WhereEnum::NE
    ]
    ,[
        'username'=>'d',
        'avatar'=>'123'
    ],
    [
        'tt'=>[
            'name as username'=>WhereEnum::LIKE
        ]
    ]
);
$res = $query->find();
$sql = \think\facade\Db::getLastSql();
dump($sql);
//dump($res);
//dump($res);