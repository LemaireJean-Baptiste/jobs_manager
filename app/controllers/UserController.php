<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\controllers;
use App\Models\User;
use App\Models\Job;
use Respect\Validation\Validator as v;

class UserController extends controller{


    public function getUsers(){
        $users = User::orderBy('created_at')
               ->get();

        return $users;

    }

    public function getUserById($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);
        return $this->view->render($response, 'user.html', ["user"=> $user ]);
    }

    public function getAdmin($request, $response){
        $users = $this->getUsers();

        return $this->view->render($response, 'admin/users.html', ["users"=> $users]);
    }

    public function changePermission($request, $response, $args){
        $id = $args['id'];
        
        $permission = $request->getParam('permission');

        if($permission != "admin"){
            $permission = "member";
        }

        $user = User::find($id);
        $user->update(['permission' => $permission]);
        $this->flash->addMessage('info', 'You change administration preferences successfully!');
        return $response->withRedirect($this->router->pathFor('admin.users'));
    }

    public function getUpdate($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);
        return $this->view->render($response, 'admin/user.html', ["user"=> $user ]);
    }

    public function postUpdate($request, $response, $args){
        
        $id = $args['id'];

        $Validation = $this->validator->validate($request, [
                'email'=>v::notEmpty()->noWhitespace()->email()->contains('@ieseg.fr'),
                'fname'=>v::notEmpty()->regex('/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]{0,50}$/'),
                'lname'=>v::notEmpty()->regex('/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]{0,50}$/'),
                'permission'=>v::notEmpty()->in(['member', 'admin'])
            ]);

        if($this->validator->failed()){
            //redirect back
            return $response->withRedirect($this->router->pathFor('admin.user.update', ['id' => $id]));
        }

        $user = User::find($id);
        
        $user->update([
                'fname'=> $request->getParam('fname'),
                'lname'=> $request->getParam('lname'),
                'email'=> $request->getParam('email') ,
                'permission'=>$request->getParam('permission')
        ]);

        $this->flash->addMessage('info', 'You updated this user profile successfully!');
        return $response->withRedirect($this->router->pathFor('admin.users'));
    }

    public function delete($request, $response, $args){
        $id = $args['id'];
        $user = User::find($id);
        $user->delete();
        $this->flash->addMessage('info', 'You deleted this user successfully!');
        return $response->withRedirect($this->router->pathFor('admin.users'));

    }
     
}