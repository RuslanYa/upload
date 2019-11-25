<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

 /**
  * This is the model class for table "user".
  *
  * @property int $id
  * @property string $name
  * @property string $login
  * @property string $parol
  */

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

     /**
      * {@inheritdoc}
      */
     public static function tableName()
     {
         return 'user';
     }

     /**
      * {@inheritdoc}
      */
     public function rules()
     {
         return [
             [['login', 'parol'], 'required'],
             [['name', 'login', 'parol'], 'string', 'max' => 255],
         ];
     }

     /**
      * {@inheritdoc}
      */
     public function attributeLabels()
     {
         return [
             'id' => 'ID',
             'name' => 'Name',
             'login' => 'Login',
             'parol' => 'Parol',
         ];
     }

    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['user_id' => 'user_id']);
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {

        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {

        return static::findOne(['login' => $username]);

    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
//        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
//        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {

        return $this->parol === $password;
    }
}
