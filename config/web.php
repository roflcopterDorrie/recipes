<?php

use \kartik\datecontrol\Module;

$params = require(__DIR__ . '/params.php');

$config = [
  'id' => 'basic',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'components' => [
    'request' => [
      // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
      'cookieValidationKey' => 'thisissecret',
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'user' => [
      'identityClass' => 'app\models\User',
      'enableAutoLogin' => TRUE,
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      // send all mails to a file by default. You have to set
      // 'useFileTransport' to false and configure a transport
      // for the mailer to send real emails.
      'useFileTransport' => TRUE,
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
    'db' => require(__DIR__ . '/db.php'),
    'authManager' => [
      'class' => '\yii\rbac\DbManager',
      'ruleTable' => 'AuthRule', // Optional
      'itemTable' => 'AuthItem', // Optional
      'itemChildTable' => 'AuthItemChild', // Optional
      'assignmentTable' => 'AuthAssignment', // Optional
    ],
    'user' => [
      'class' => 'auth\components\User',
      'identityClass' => 'auth\models\User',
      // or replace to your custom identityClass
      'enableAutoLogin' => TRUE,
    ],
    'urlManager' => [
      'class' => 'yii\web\UrlManager',
      // Disable index.php
      //'showScriptName' => false,
      // Disable r= routes
      'enablePrettyUrl' => TRUE,
      'rules' => [
        '<controller:\w+>/<id:\d+>' => '<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
      ],
    ],
  ],
  'modules' => [
    'auth' => [
      'class' => 'auth\Module',
      'layout' => '//main',
      // Layout when not logged in yet
      'layoutLogged' => '//main',
      // Layout for logged in users
      'attemptsBeforeCaptcha' => 3,
      // Optional
      'supportEmail' => 'dylan@dylanrolfe.com.au',
      // Email for notifications
      'passwordResetTokenExpire' => 3600,
      // Seconds for token expiration
      'superAdmins' => ['admin'],
      // SuperAdmin users
      //'signupWithEmailOnly' => false, // false = signup with username + email, true = only email signup
      'tableMap' => [ // Optional, but if defined, all must be declared
        'User' => 'user',
        'UserStatus' => 'user_status',
        'ProfileFieldValue' => 'profile_field_value',
        'ProfileField' => 'profile_field',
        'ProfileFieldType' => 'profile_field_type',
      ],
    ],
    'datecontrol' => [
      'class' => 'kartik\datecontrol\Module',
      'displaySettings' => [
        Module::FORMAT_DATE => 'dd/MM/yyyy',
        Module::FORMAT_TIME => 'HH:mm:ss a',
        Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss a',
      ],
    ],
  ],
  'params' => $params,
];
////
//if (YII_ENV_DEV) {
// configuration adjustments for 'dev' environment
$config['bootstrap'][] = 'debug';
$config['modules']['debug'] = 'yii\debug\Module';

$config['bootstrap'][] = 'gii';
$config['modules']['gii'] = [
  'class' => 'yii\gii\Module',
  'allowedIPs' => ['192.168.*.*'],
];
//}

return $config;
