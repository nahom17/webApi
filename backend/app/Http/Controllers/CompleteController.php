<?php


namespace App\Http\Controllers;
use App\Models\CompleteModel;

//use App\Models\UserModel;

class CompleteController
{

    public function index()
    {
        $completeModel = new CompleteModel();
        $completeModel->index();
    }

    public function edit()
    {
        $completeModel = new CompleteModel();
        $completeModel->edit();
    }

    public function create()
    {
        $completeModel = new CompleteModel();
        $completeModel->create();
    }

    public function update()
    {
        $completeModel = new CompleteModel();
        $completeModel->update();
    }

    public function destroy()
    {
        $completeModel = new CompleteModel();
        $completeModel->destroy();
    }
}

