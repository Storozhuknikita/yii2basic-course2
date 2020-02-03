<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    /**
     * RBAC
     */
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
            'as access' => [
                'class' => yii2mod\rbac\filters\AccessControl::class
            ],
        ],
        'test' => [
            'class' => 'frontend\modules\test\Module',
        ],
        'api' => [
            'class' => \frontend\modules\api\Module::class
        ],
        'account' => [
            'class' => \frontend\modules\account\Module::class
        ],

    ],

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // ЧПУ
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

                /*[
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                ],*/
                [
                    'controller' => 'api/task',
                    'class' => \yii\rest\UrlRule::class,
                    'extraPatterns' => [
                        //'METHOD action' => 'actionFunction',
                        'POST random/<count>' => 'random',
                        'GET data-provider/<limit>' => 'data-provider',
                        'GET auth' => 'auth',
                    ],
                ],

                [
                    'controller' => 'api/user',
                    'class' => \yii\rest\UrlRule::class,
                    'extraPatterns' => [
                    ],
                ],
            ],
        ],
        /**
         * RBAC AUTH
         */
        'authManager' => [
            'class'           => 'yii\rbac\DbManager',
            'itemTable'       => 'auth_item',
            'itemChildTable'  => 'auth_item_child',
            'assignmentTable' => 'auth_assignment',
            'ruleTable'       => 'auth_rule',
            'defaultRoles'    => ['guest'],// роль которая назначается всем пользователям по умолчанию
        ],

        /**
         * Настройки темизации
         */
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/news', // базовая директория со стилизованными ресурсами
                'baseUrl' => '@web/themes/news', // базовый адрес доступа к стилизованным ресурсам
                'pathMap' => [ // правила замены файлов view
                    '@app/views/hello' => '@app/themes/new/hello',
                    //'@app/modules' => '@app/themes/news/modules',
                    //'@app/widgets' => '@app/themes/news/widgets',
                ],
            ],
        ],
    ],

    'params' => $params,
];
