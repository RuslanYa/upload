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

    <?php
//    var_dump($_SESSION);
//    var_dump($_POST);



    ?>



        </div>


        <div class="col-md-9">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs_frame']) ? $this->params['breadcrumbs_frame'] : [],
            ]) ?>
            <?php
            $table = '<table class="table table-striped">';



            foreach ($tree as $key=>$item){

                if (is_dir($item) ){
                    if ($item == '..'){
                        $table .= "<tr><td><a href='' class='file-link' data-inf='$item'>$item</a></td><td></td><td></td><td></td></tr>";
                    }else{
                    $table .= "<tr>
                                    <td><a href='' class='file-link' data-inf='$item'>$item</a></td>
                                    <td><a href='#' data-path='$path' data-name='$item' class='rename'>Rename</a></td>
                                    <td><a href='' class='delete' data-path='$path' data-name='$item'>Delete</a></td>
                                    <td></td>
                              </tr>";
                    }
                }else{

                      $table .= "<tr><td>$item</td>
                                     
                                     <td><a href='#' data-path='$path' data-name='$item' class='rename'>Rename</a></td>
                                     <td><a href='' class='delete' data-path='$path' data-name='$item'>Delete</a></td>
                                     <td><a href='download?path=$path&name=$item' data-path='$path' data-name='$item' >Download</a></td>
                                 </tr>";

                }
            }
            $table .= '</table>';
            echo $table;

            ?>





            <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel"> </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5>Введите новое имя</h5>
                            <?php echo \yii\helpers\Html::input('text', 'input', ''); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary safe-input">Сохранить изменения</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



