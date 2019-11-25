<?php
namespace app\models;
use yii\base\Model;

class SignupForm extends Model{

    public $login;
    public $parol;
    public $name;

    public function rules() {
    return [
    [['login', 'parol','name'], 'required', 'message' => 'Заполните поле'],
    ];
    }

    public function attributeLabels() {
    return [
    'login' => 'Логин',
    'parol' => 'Пароль',
        'name' => 'Имя',
    ];
    }

}