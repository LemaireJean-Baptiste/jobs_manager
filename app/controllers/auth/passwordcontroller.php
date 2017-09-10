<?php


namespace App\controllers\auth; 
use App\Models\User;
use App\controllers\controller;
use Respect\Validation\Validator as v;

class PasswordController extends controller{
    
    public function getChangePassword($request, $response){
        return $this->view->render($response, 'auth/changePassword.html');
    }
     public function postChangePassword($request, $response){
        $validation = $this->validator->validate($request, [
                'password_old'=>v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
                'password'=> v::noWhitespace()->notEmpty(),
            ]);

        if($this->validator->failed()){
            $this->flash->addMessage('error', 'Error! The old password didn\'t match.');
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }
        $this->auth->user()->setPassword($request->getParam('password'));
        $this->flash->addMessage('info', 'Your password was Changed.');

        return $response->withRedirect($this->router->pathFor('home'));
    }

     
}