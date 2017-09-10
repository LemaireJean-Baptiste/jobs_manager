<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\controllers;
use App\Models\Recruit;
use App\Models\Recruit_detail;
use Respect\Validation\Validator as v;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RecruitController extends controller{

    public function getRecruits($all=false){


        if($all){
            $recruits = Recruit::orderBy('created_at')
                   ->get();
        }else{
            $recruits = Recruit::where('status', '=', 1)
                   ->orderBy('created_at')
                   ->get();
        }

        return $recruits;

    }

    public function index($request, $response){
        
        $recruit_details = Recruit_detail::find(1);

        if($recruit_details['status'] == 1){
            $recruits = $this->getRecruits();
        }else{
            $recruits = false;
        }
        
        return $this->view->render($response, 'recruits.html', ["recruits"=> $recruits, "details" => $recruit_details ]);

    }

    public function getRecruitById($request, $response, $args){
        $recruit_details = Recruit_detail::find(1);

        if($recruit_details['status'] == 1){
            $id = $args['id'];
            
            try {
                $recruit = Recruit::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                throw new \Slim\Exception\NotFoundException($request, $response);
            }

            if($recruit['status'] == 0){
                throw new \Slim\Exception\NotFoundException($request, $response);
            }

        }



        return $this->view->render($response, 'recruit.html', ["recruit"=> $recruit, "recruit_deadline"=> $recruit_details['deadline']]);
    }

    public function getAdmin($request, $response){
        $recruits = $this->getRecruits(true);

        $recruit_details = Recruit_detail::find(1);

        return $this->view->render($response, 'admin/recruits.html', ["recruits"=> $recruits, "recruit_details"=> $recruit_details]);
    }

    public function postRecruitDetails($request, $response, $args){
         $Validation = $this->validator->validate($request, [
                'text'=>v::notEmpty()->length(null,500),
                'deadline'=>v::notEmpty()
            ]);

        if($this->validator->failed()){
            //redirect back
            return $response->withRedirect($this->router->pathFor('admin.recruits'));
        }

        if($request->getParam('status')){
            $status = 1;
        }else{
            $status = 0;
        }

        $id = $args['id'];
        $recruit = Recruit_detail::find(1);
        
        $deadline = date('Y-m-d', strtotime($request->getParam('deadline') . ' +1 day'));

        $recruit->update([
            'text'=> $request->getParam('text'),
            'deadline'=> $deadline,
            'status'=> $status
        ]);

        $this->flash->addMessage('info', 'You updated the recruitment details successfully!');
        return $response->withRedirect($this->router->pathFor('admin.recruits'));
    }

    public function changeStatus($request, $response, $args){
        $id = $args['id'];
        
        $status = (int)$request->getParam('status');

        if($statut != 1){
            $statut = 0;
        }

        $recruit = Recruit::find($id);
        $recruit->update(['status' => $status]);
        $this->flash->addMessage('info', 'You change status of the recruit successfully!');
        return $response->withRedirect($this->router->pathFor('admin.recruits'));
    }

    public function getNew($request, $response){
        return $this->view->render($response, 'admin/recruit_new.html');
    }

    public function postNew($request, $response){
        $Validation = $this->validator->validate($request, [
                'name'=>v::notEmpty()->length(null,50),
                'deadline'=>v::notEmpty()
            ]);

        if($this->validator->failed()){
            $this->flash->addMessage('error', 'Error : At least one field was not correctly filled.');
            return $response->withRedirect($this->router->pathFor('admin.recruit.new'));
        }
        
        $recruit = Recruit::create([
                'name'=> $request->getParam('name'),
                'excerpt'=> $request->getParam('excerpt'),
                'text'=> $request->getParam('text'),
                'deadline'=> $request->getParam('deadline')
            ]);

        $this->flash->addMessage('info', 'You added a new recruit successfully!');
        return $response->withRedirect($this->router->pathFor('admin.recruits'));
    }

    public function getUpdate($request, $response, $args){
        $id = $args['id'];

        try {
            $recruit = Recruit::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', 'Error 404 : This page do not exist!');
            return $response->withRedirect($this->router->pathFor('admin.recruits'));
        }

        return $this->view->render($response, 'admin/recruit.html', ["recruit"=> $recruit ]);
    }

    public function postUpdate($request, $response, $args){
        $Validation = $this->validator->validate($request, [
                'name'=>v::notEmpty()->length(null,50),
                'deadline'=>v::notEmpty()
            ]);

        if($this->validator->failed()){
            $this->flash->addMessage('error', 'Error : At least one field was not correctly filled.');
            return $response->withRedirect($this->router->pathFor('admin.recruit.new'));
        }

        $id = $args['id'];
        $recruit = Recruit::find($id);
        
        $recruit->update([
            'name'=> $request->getParam('name'),
            'excerpt'=> $request->getParam('excerpt'),
            'text'=> $request->getParam('text'),
            'salary'=> $request->getParam('salary'),
            'deadline'=> $request->getParam('deadline')
        ]);

        $this->flash->addMessage('info', 'You updated this post successfully!');
        return $response->withRedirect($this->router->pathFor('admin.recruits'));
    }

    public function delete($request, $response, $args){
        $id = $args['id'];
        $recruit = Recruit::find($id);
        $recruit->delete();
        $this->flash->addMessage('info', 'You deleted this post successfully!');
        return $response->withRedirect($this->router->pathFor('admin.recruits'));

    }
    
     
}