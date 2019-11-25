<?php
use yii\widgets\ActiveForm;
//use yii\helpers\Html;
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
//$this->title = 'upload';
//$this->params['breadcrumbs'][] = $this->title;
/*$this->params['breadcrumbs_frame'][] = [
//    'template' => "<li>{link}</li>\n", //  шаблон для этой ссылки
    'label' => 'Категория', // название ссылки
    'url' => ['/category'] // сама ссылка
];*/
/*$_SESSION['full_dir_path'] ='';
foreach ($_SESSION['dir_path'] as $item){
    $_SESSION['full_dir_path'] .= $item;
    $this->params['breadcrumbs_frame'][] = ['label' => $item, 'url' => 'upload?'. $item];
}*/
//$this->params['breadcrumbs_frame'][] = ['label' => $_SESSION['dir_path'], 'url' => 'upload?'. $_SESSION['dir_path']];
?>
<div class="wrap">
    <div class="row" >

        <div class="col-md-3" >

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?php echo $form->field($model, 'imageFile')->fileInput() ?>
            <button>Загрузить</button>
            <?php ActiveForm::end() ?>
            <br>


            <?php echo Html::beginForm(['upload'], 'post', ['enctype' => 'multipart/form-data']) ?>
            <?php echo Html::label('Введите название папки')?>
            <?php echo Html::input('text','new_dir') ?>
            <br><br>
            <?php echo Html::submitButton('Создать папку', [
                'class' => 'btn btn-primary btn-sm',
            ]) ;
            ?>
            <?php Html::endForm(); ?>

    <?php var_dump($_SESSION);
    var_dump($_POST);



    ?>



        </div>


        <div class="col-md-9">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs_frame']) ? $this->params['breadcrumbs_frame'] : [],
            ]) ?>
            <?php echo $table; ?>


    <button id="btn1">ajax</button>
        <p id="par1">u</p>
        </div>
    </div>
</div>



