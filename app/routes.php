<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\AdminMiddleware;

$app->get('/', 'indexcontroller:index')->setName('home');

$app->get('/jobs[/{level}]', 'jobcontroller:index')->setName('jobs');
$app->get('/job/{id:[0-9]+}', 'jobcontroller:getJobById')->setName('job');

$app->get('/recruits', 'recruitcontroller:index')->setName('recruits');
$app->get('/recruit/{id:[0-9]+}', 'recruitcontroller:getRecruitById')->setName('recruit');

$app->get('/multiple', 'authcontroller:multipleUsers');

$app->group('/admin', function(){
	$this->get('', 'jobcontroller:getAdmin')->setName('admin');
	
	$this->get('/jobs', 'jobcontroller:getAdmin')->setName('admin.jobs');
	$this->get('/job/new', 'jobcontroller:getNew')->setName('admin.job.new');
	$this->post('/job/new', 'jobcontroller:postNew');
	$this->get('/job/{id:[0-9]+}', 'jobcontroller:getUpdate')->setName('admin.job.update');
	$this->post('/job/{id:[0-9]+}', 'jobcontroller:postUpdate');
	$this->delete('/job/{id:[0-9]+}', 'jobcontroller:delete')->setName('admin.job.delete');

	$this->get('/recruits', 'recruitcontroller:getAdmin')->setName('admin.recruits');
	$this->get('/recruit/new', 'recruitcontroller:getNew')->setName('admin.recruit.new');
	$this->post('/recruit/new', 'recruitcontroller:postNew');
	$this->get('/recruit/{id:[0-9]+}', 'recruitcontroller:getUpdate')->setName('admin.recruit.update');
	$this->post('/recruit/{id:[0-9]+}', 'recruitcontroller:postUpdate');
	$this->delete('/recruit/{id:[0-9]+}', 'recruitcontroller:delete')->setName('admin.recruit.delete');
	$this->post('/recruit/{id:[0-9]+}/changeStatus', 'recruitcontroller:changeStatus')->setName('admin.recruit.changeStatus');
	$this->post('/recruits/updateDetails', 'recruitcontroller:postRecruitDetails')->setName('admin.recruit_details');


	$this->get('/users', 'usercontroller:getAdmin')->setName('admin.users');
	$this->get('/user/{id:[0-9]+}', 'usercontroller:getUpdate')->setName('admin.user.update');
	$this->post('/user/{id:[0-9]+}', 'usercontroller:postUpdate');
	$this->delete('/user/{id:[0-9]+}', 'usercontroller:delete')->setName('admin.user.delete');

	$this->post('/user/{id:[0-9]+}/changePermission', 'usercontroller:changePermission')->setName('admin.user.changePermission');

})->add(new AdminMiddleware($container));

$app->group('', function(){
	$this->get('/signup', 'authcontroller:getsignup')->setName('auth.signup');
	$this->post('/signup', 'authcontroller:postsignup');

	$this->get('/signin', 'authcontroller:getSignIn')->setName('auth.signin');
	$this->post('/signin', 'authcontroller:postSignIn');

})->add(new GuestMiddleware($container));

$app->group('', function(){
	$this->get('/signout', 'authcontroller:getsignout')->setName('auth.signout');

	$this->get('/change-password', 'passwordcontroller:getChangePassword')->setName('auth.password.change');
	$this->post('/change-password', 'passwordcontroller:postChangePassword');
})->add(new AuthMiddleware($container));

