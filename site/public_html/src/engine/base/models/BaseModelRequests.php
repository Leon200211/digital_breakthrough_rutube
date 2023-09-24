<?php


namespace engine\base\models;


// трейт с базовыми запросами к БД
trait BaseModelRequests
{



    // метод для получения ролей пользователя
    public function getUserRoles($id_user){

        $userRoles = [];

        // ищем все роли пользователя
        $roles = $this->read('user_roles', [
            'where' => [
                'id_user' => $id_user,
            ],
            'operand' => ['='],
            'join' => [
                [
                    'table' => 'roles',
                    'fields' => ['title'],
                    'type' => 'inner',
                    'operand' => ['='],
                    'condition' => ['AND'],
                    'on' => ['id_role', 'id']
                ]
            ]
        ]);

        // создаем массив с ролями пользователя
        foreach ($roles as $value){
            $userRoles[] = ['role_id' => $value['id_role'],
                            'role_title' => $value['title']];
        }

        return $userRoles;

    }


    // метод для получения фото профиля
    public function getUserImg($id_user){

        $img = $this->read('users', [
            'fields' => ['photo'],
            'where' => [
                'id' => $id_user,
            ],
            'operand' => ['='],
        ])[0]['photo'];

        if(file_exists(USER_PROFILE_IMG . $img)){
            return $img;
        }else{
            return 'default_img_user.png';
        }


    }


}