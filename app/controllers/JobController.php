<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\controllers;
use App\Models\Job;
use Respect\Validation\Validator as v;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobController extends controller{

    public function redirect($request, $response){
        return $response->withRedirect($this->router->pathFor('jobs'));
    }

    public function getJobs($level, $all=false){

        if($level=="B1" or $level=="B2" or $level=="B3" or $level=="Master"){
            
            switch ($level) {
                case 'B1':
                    $levelCheck = ['B1'];
                    break;
                
                case 'B2':
                    $levelCheck = ['B1', 'B2'];
                    break;

                case 'B3':
                    $levelCheck = ['B1', 'B2', 'B3'];
                    break;

                case 'Master':
                    $levelCheck = ['B1', 'B2', 'B3', 'Master'];
                    break;

            }


            $jobs = Job::where('deadline', '>=', date("Y-m-d"))
                        ->whereIn('level',$levelCheck)
                        ->orderBy('created_at')
                        ->get();

        }else{
            $jobs = Job::where('deadline', '>=', date("Y-m-d"))
                   ->orderBy('created_at')
                   ->get();
        }

        if($all){
            $jobs = Job::orderBy('created_at')
                   ->get();
        }

        return $jobs;

    }

    public function index($request, $response, $args){
        $level = $args['level'];
        $jobs = $this->getJobs($level);

        return $this->view->render($response, 'jobs.html', ["jobs"=> $jobs, 'level'=> $args['level'] ]);

    }

    public function getJobById($request, $response, $args){
        $id = $args['id'];

        try {
            $job = Job::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        return $this->view->render($response, 'job.html', ["job"=> $job, "user"=>$user]);
    }

    public function getAdmin($request, $response){
        $jobs = $this->getJobs(null, true);

        return $this->view->render($response, 'admin/jobs.html', ["jobs"=> $jobs]);
    }

    public function getNew($request, $response){
        return $this->view->render($response, 'admin/job_new.html');
    }

    public function postNew($request, $response){
        $Validation = $this->validator->validate($request, [
                'name'=>v::notEmpty()->length(null,50),
                'start'=>v::notEmpty(),
                'finish'=>v::notEmpty(),
                'deadline'=>v::notEmpty(),
                'salary'=>v::notEmpty(),
                'level'=>v::notEmpty()->oneOf(v::equals('B1'), v::equals('B2'), v::equals('B3'), v::equals('Master'))
            ]);

        if($this->validator->failed()){
            $this->flash->addMessage('error', 'Error : At least one field was not correctly filled.');
            return $response->withRedirect($this->router->pathFor('admin.job.new'));
        }
        
        $user = Job::create([
                'name'=> $request->getParam('name'),
                'excerpt'=> $request->getParam('excerpt'),
                'text'=> $request->getParam('text'),
                'start'=> $request->getParam('start'),
                'finish'=> $request->getParam('finish'),
                'salary'=> $request->getParam('salary'),
                'deadline'=> $request->getParam('deadline'),
                'level'=> $request->getParam('level'),
            ]);

        $this->flash->addMessage('info', 'You added a new job successfully!');
        return $response->withRedirect($this->router->pathFor('admin.jobs'));
    }

    public function getUpdate($request, $response, $args){
        $id = $args['id'];

        try {
            $job = Job::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->flash->addMessage('error', 'Error 404 : This page do not exist!');
            return $response->withRedirect($this->router->pathFor('admin.jobs'));
        }

        return $this->view->render($response, 'admin/job.html', ["job"=> $job ]);
    }

    public function postUpdate($request, $response, $args){
        $Validation = $this->validator->validate($request, [
                'name'=>v::notEmpty()->length(null,50),
                'start'=>v::notEmpty(),
                'finish'=>v::notEmpty(),
                'deadline'=>v::notEmpty(),
                'salary'=>v::notEmpty(),
                'level'=>v::notEmpty()->oneOf(v::equals('B1'), v::equals('B2'), v::equals('B3'), v::equals('Master'))
            ]);

        if($this->validator->failed()){
            $this->flash->addMessage('error', 'Error : At least one field was not correctly filled.');
            return $response->withRedirect($this->router->pathFor('admin.job.new'));
        }

        $id = $args['id'];
        $job = Job::find($id);
        
        $job->update([
            'name'=> $request->getParam('name'),
            'excerpt'=> $request->getParam('excerpt'),
            'text'=> $request->getParam('text'),
            'start'=> $request->getParam('start'),
            'finish'=> $request->getParam('finish'),
            'salary'=> $request->getParam('salary'),
            'deadline'=> $request->getParam('deadline'),
            'level'=> $request->getParam('level'),
        ]);

        $this->flash->addMessage('info', 'You updated this post successfully!');
        return $response->withRedirect($this->router->pathFor('admin.jobs'));
    }

    public function delete($request, $response, $args){
        $id = $args['id'];
        $job = Job::find($id);
        $job->delete();
        $this->flash->addMessage('info', 'You deleted this post successfully!');
        return $response->withRedirect($this->router->pathFor('admin.jobs'));

    }
    
     
}