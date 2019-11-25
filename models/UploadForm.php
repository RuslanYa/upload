<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {

            if (!file_exists('/web/uploads/' . $_SESSION['full_dir_path'])) {

                FileHelper::createDirectory('uploads/' . $_SESSION['full_dir_path']);

            }

                $this->imageFile->saveAs('uploads/'. $_SESSION['full_dir_path'] .'/'. $this->imageFile->baseName . '.' . $this->imageFile->extension);

            return true;
        } else {

            return false;
        }

    }



}