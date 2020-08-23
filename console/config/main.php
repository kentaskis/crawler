<?php

use yii\log\FileTarget;
use aleksandrhorkavyi\crawler\collectors\DataCollector;
use aleksandrhorkavyi\crawler\collectors\senders\EmailSender;
use aleksandrhorkavyi\crawler\collectors\senders\TelegramSender;
use aleksandrhorkavyi\crawler\components\CrawlerThread;
use aleksandrhorkavyi\crawler\components\FileMarkup;
use aleksandrhorkavyi\crawler\CrawlerModule;
use Facebook\WebDriver\Chrome\ChromeDriver;
use yii\queue\redis\Queue;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','queue'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'logFile' => '@runtime/logs/crawler.log',
                    'levels' => ['error', 'warning'],
                    'categories' => 'parser'
                ],

                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'redis' => [
            'class' => yii\redis\Connection::class,
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'queue' => [
            'class' => Queue::class,
            'redis' => 'redis',
            'channel' => 'queue', // Ключ канала очереди
        ],
    ],
    'params' => $params,
];
