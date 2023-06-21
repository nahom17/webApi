<?php

namespace App\Models;

use App\Database\Database;

class TodoModel
{
    public static function all(): array
    {
        Database::query("
            SELECT `todos`.*,`users`.`username` AS `username`
            FROM `todos`
            LEFT JOIN `users` ON `todos`.`user_id` = `users`.`id`
            GROUP BY `todos`.`id`

        ");

        return Database::getAll();
    }

    public static function find(int $id): array
    {
        Database::query("
            SELECT `todos`.*,`users`.`username` AS `username`
            FROM `todos`
            LEFT JOIN `users` ON `todos`.`user_id` = `users`.`id`
            WHERE `todos`.`id` = :id
            GROUP BY `todos`.`id`

        ", [':id' => $id]);

        return Database::get();
    }

    public static function create(array $data):array
    {
        Database::query(
            'INSERT INTO  `todos`(`user_id`,`task`,`startdate`,`enddate`,`done`)
            VALUES(:user_id,:task,:startdate,:enddate,:done)',
            [
                ':user_id' => $data['user_id'],
                ':task' => $data['task'],
                ':startdate' =>date('Y-m-d H:i:s'),
                ':enddate' => date('Y-m-d H:i:s'),
                ':done' => $data['done']

            ]

        );
        return ['message'=>'todo created.' , 'id'=> Database::lastId()] ?? [];
    }

    public function update()
    {
        echo 'TodoModel -> update';
    }

    public function destroy()
    {
        echo 'TodoModel -> destroy';
    }
}
