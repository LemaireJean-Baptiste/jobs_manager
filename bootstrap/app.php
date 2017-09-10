<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Respect\Validation\Validator as v;
use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;

session_start();

require __DIR__ .'/../vendor/autoload.php';


$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails' => true,
        'db'=> [
            'driver'=>'mysql',
            'host'=>'localhost',
            'database'=>'ic_crm',
            'username'=>'root',
            'password'=>'root',
            'collation'=>'utf_unicode_ci',
            'prefix'=>''
        ],
    ]
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


$container['db'] = function ($container) use ($capsule){
    return $capsule;  
};

$container['auth'] = function ($container){
    return new \App\Auth\Auth;
};

$container['view'] = function ($container){
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);
    
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->getEnvironment()->addGlobal('auth', $container->auth,[
            'check'=> $container->auth->check(),
            'user' =>$container->auth->user(),
        ]);
    $view->getEnvironment()->addGlobal('flash', $container->flash);

    
    $view['baseUrl'] = 'http://localhost:8888/Developpement/slim/public';

    $engine = new MarkdownEngine\MichelfMarkdownEngine();
    $view->addExtension(new MarkdownExtension($engine));

    $duration = new Twig_SimpleFilter('duration', function ($start, $finish) { // ex : {{ "now"|date("Y-m-d") | duration(recruit.deadline)}}
        $first_date = new DateTime($start);
        $second_date = new DateTime($finish);

        $interval = $first_date->diff($second_date);

        $result = "";
        if ($interval->y) { $result .= $interval->format("%y a. "); }
        if ($interval->m) { $result .= $interval->format("%m m. "); }
        if ($interval->d) { 
            /*$numberOfDay = (int)($interval->d);

            if($numberOfDay > 7){
                $weeks = (int)($numberOfDay / 7);
                $results = $results .  $weeks . " sem. ";

                if($days = $numberOfDay % 7){
                    $results = $results . $days . " j.";
                } 
            }else{}*/     
                $result .= $interval->format("%d j."); 
        }else{
            $result .= "0 j."; 
        }



        return $result;
    });

    $view->getEnvironment()->addFilter($duration);

    $excerpt = new Twig_SimpleFilter('excerpt', function ($string, $length) { // {{ string | excerpt(length) }}
        
        $excerpt = $string;

        if( strlen(utf8_decode($string)) > $length){
            $excerpt = substr( $string, 0, $length ) . " ...";
        }

        return $excerpt;
    });
    
    $view->getEnvironment()->addFilter($excerpt);

    return $view;
};

$container['validator'] = function ($container){
    return new App\Validation\Validator;
};
$container['jobcontroller'] = function($container){
    return new \App\controllers\jobcontroller($container);
};
$container['usercontroller'] = function($container){
    return new \App\controllers\usercontroller($container);
};
$container['recruitcontroller'] = function($container){
    return new \App\controllers\recruitcontroller($container);
};
$container['indexcontroller'] = function($container){
    return new \App\controllers\indexcontroller($container);
};

$container['authcontroller'] = function($container){
    return new \App\controllers\auth\authcontroller($container);
};
$container['passwordcontroller'] = function($container){
    return new \App\controllers\auth\passwordcontroller($container);
};
$container['csrf'] = function ($container){
    return new \Slim\Csrf\Guard;
};
$container['flash'] = function ($container){
    return new \Slim\Flash\Messages;
};

$container['notFoundHandler'] = function ($c) { 
    return new \App\Action\NotFoundHandler($c->get('view'), function ($request, $response) use ($c) { 
        return $c['response'] 
            ->withStatus(404); 
    }); 
};


$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');

require __DIR__.'/../app/routes.php';

