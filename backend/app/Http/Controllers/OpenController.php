<?php


namespace App\Http\Controllers;

use App\Models\OpenModel;

class OpenController
{

    public function index()
    {
        $openModel = new OpenModel();
        $openModel->index();
    }

    public function edit()
    {
        $openModel = new OpenModel();
        $openModel->edit();
    }

    public function create()
    {
        $openModel = new OpenModel();
        $openModel->create();
    }

    public function update()
    {
        $openModel = new OpenModel();
        $openModel->update();
    }

    public function destroy()
    {
        $openModel = new OpenModel();
        $openModel->destroy();
    }
}