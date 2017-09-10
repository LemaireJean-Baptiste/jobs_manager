<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\controllers\auth; 
use App\Models\User;
use App\controllers\controller;
use Respect\Validation\Validator as v;

class authController extends controller{
    
    public function getsignout($request, $response){
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }    

    public function getsignup($request, $response){
        
        return $this->view->render($response, 'auth/signup.html');
    }
    
    public function postsignup($request, $response){
		    	
		$Validation = $this->validator->validate($request, [
                'email'=>v::notEmpty()->noWhitespace()->email()->contains('@ieseg.fr')->EmailAvailable(),
                'fname'=>v::notEmpty()->regex('/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]{0,50}$/'),
                'lname'=>v::notEmpty()->regex('/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]{0,50}$/'),
                'password'=>v::notEmpty()->noWhitespace(),
            ]);

        if($this->validator->failed()){
            //redirect back
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }
		
        $user = User::create([
        		'fname'=> $request->getParam('fname'),
                'lname'=> $request->getParam('lname'),
        		'email'=> $request->getParam('email') ,
        		'password'=> password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        	]);
        $auth =  $this->auth->attempt($user->email, $request->getParam('password'));

        $this->flash->addMessage('info', 'You have signed up successfully!');
        return $response->withRedirect($this->router->pathFor('home'));
    }


    public function multipleUsers($request, $response){
        for ($i=0; $i < 100; $i++) { 
            $user = User::create([
                'fname'=> 'Jeanne '.$i,
                'lname'=> 'Masse '.$i,
                'email'=> 'jeanne'.$i.'.masse'.$i.'@ieseg.fr' ,
                'password'=> password_hash('jeanne'.$i, PASSWORD_DEFAULT),
            ]);
        }
        return $response->withRedirect($this->router->pathFor('home'));

    }

    public function getSignIn($request, $response){
         return $this->view->render($response, 'auth/signin.html');
    }
    public function postSignIn($request, $response){
        $auth =  $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password'));
        if(!$auth){
            $this->flash->addMessage('error', 'Could not sign you in!');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
        
        $this->flash->addMessage('info', 'It\'s good to see you again !');
        return $response->withRedirect($this->router->pathFor('home'));
    }
     
}