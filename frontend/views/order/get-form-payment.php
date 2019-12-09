<?phpuse backend\modules\products\models\Payments;use yii\helpers\Html;use yii\bootstrap\ActiveForm;use appxq\sdii\helpers\SDNoty;use appxq\sdii\helpers\SDHtml;use yii\web\View;?>    <div class="payments-form">        <div class="row">            <div class="col-md-6">                <?php $form = ActiveForm::begin([                    'id'=>$model->formName(),                ]); ?>                <div class="modal-body">                    <?= $form->field($model, 'order_id')->hiddenInput()->label(false) ?>                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>                    <?= $form->field($model, 'image')->fileInput() ?>                    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>                    <?= $form->field($model, 'date')->textInput(['type'=>'date']) ?>                </div>                <div class="modal-footer">                    <div class="row">                        <div class="col-md-6 col-md-offset-3">                            <?= Html::submitButton('ยืนยัน', ['class' => 'btn btn-primary btn-lg btn-block btn-submit']) ?>                        </div>                    </div>                </div>                <?php ActiveForm::end(); ?>            </div>        </div>    </div><?php  \richardfan\widget\JSRegister::begin(['position' => \yii\web\View::POS_READY]); ?>    <script>            function initPayment(){                let url = '<?= \yii\helpers\Url::to(['/order/preview-payment'])?>';                let order_id = '<?= $model->order_id?>';                $.get(url,{order_id:order_id},function (result) {                    $("#payment-preview").html(result);                });                return false;            }        // JS script        $('form#<?= $model->formName()?>').on('beforeSubmit', function (e) {            $('.btn-submit').prepend('<span class="icon-spin"><i class="fa fa-spinner fa-spin"></i></span> ');            $('.btn-submit').attr('disabled',true);            var $form = $(this);            var formData = new FormData($(this)[0]);            $.ajax({                url: $form.attr('action'),                type: 'POST',                data: formData,                processData: false,                contentType: false,                cache: false,                enctype: 'multipart/form-data',                success: function (result){                    $('.btn-submit .icon-spin').remove();                    $('.btn-submit').attr('disabled',false);                    if (result.status == 'success') {                        swal({                            title: result.status,                            text: result.message,                            type: result.status,                            timer: 1000                        });                        initPayment();                        $('form').find("input[type=file],input[type=text], textarea").val("");                        $("#payment-form").collapse('toggle');                    } else {                        swal({                            title: result.status,                            text: result.message,                            type: result.status,                            //timer: 1000                        });                    }                }            }).fail(function (err) {                $('.btn-submit .icon-spin').remove();                $('.btn-submit').attr('disabled',false);            });            return false;        });    </script><?php  \richardfan\widget\JSRegister::end(); ?>