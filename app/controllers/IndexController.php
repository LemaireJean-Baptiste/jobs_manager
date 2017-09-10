<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\controllers;
use App\Models\Job;
use App\Models\Recruit;
use App\Models\Recruit_detail;

class IndexController extends controller{

    public function index($request, $response){

        $nbJob = Job::where('deadline', '>=', date("Y-m-d"))->count();
        
        $recruit_details = Recruit_detail::find(1);
        
        if($recruit_details['deadline'] > date('Y-m-d H:i:s') and $recruit_details['status'] == 1){
            $recruits = Recruit::where('status', '=', 1)->get();
            $nbRecruit = 0;
            foreach ($recruits as $recruit) {
                $nbRecruit += $recruit['number'];
            }
        }else{
            $recruits = false;
        }

        $number = ['job' => $nbJob, 'recruit' => $nbRecruit];

        return $this->view->render($response, 'index.html', ["number"=> $number]);
    }

     
}