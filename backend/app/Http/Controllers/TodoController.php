<?php

namespace App\Http\Controllers;

use App\Models\TodoModel;

class TodoController
{
    public function index(): array
    {
        return TodoModel::all();
    }

    public function show(int $id): array
    {

        return TodoModel::find($id);
    }

    public function create( array $request): array
    {

        return TodoModel::create($request);
    }

    public function update():array
    {
        return [];
    }

    public function destroy()
    {
        return [];
    }
}
