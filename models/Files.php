<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $user_id
 * @property int $dir_path
 * @property int $file_path
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'dir_path', 'file_path'], 'required'],
            [['id', 'dir_path', 'file_path'], 'integer'],
            [['user_id'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'dir_path' => 'Dir Path',
            'file_path' => 'File Path',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
}
