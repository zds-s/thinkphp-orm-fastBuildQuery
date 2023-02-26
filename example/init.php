<?php
include dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
$noIncludeFiles = ['.','..','init.php','fastQuery.php'];
\think\facade\Db::setConfig([
    // 默认数据连接标识
    'default'     => 'mysql',
    // 数据库连接信息
    'connections' => [
        'mysql' => [
            // 数据库类型
            'type'     => 'mysql',
            // 主机地址
            'hostname' => 'localhost',
            // 用户名
            'username' => 'root',
            'password' => '44e1ca802506a97d',
            // 数据库名
            'database' => 'chat-server',
            'hostport'=>3306,
            // 数据库编码默认采用utf8
            'charset'  => 'utf8',
            // 数据库表前缀
            'prefix'   => '',
            // 数据库调试模式
            'debug'    => true,
        ],
    ],
]);
foreach (scandir(__DIR__) as $file){
    if (!in_array($file,$noIncludeFiles)){
        $filePath = __DIR__.DIRECTORY_SEPARATOR.$file;
        include $filePath;
    }
}

