<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use appxq\sdii\widgets\GridView;
use appxq\sdii\widgets\ModalForm;
use appxq\sdii\helpers\SDNoty;
use appxq\sdii\helpers\SDHtml;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\products\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'รายการสินค้า');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-primary">
    <div class="box-header">
         <?= $this->render('_icon');?> <?=  Html::encode($this->title) ?>
         <div class="pull-right">
             <?= Html::button(SDHtml::getBtnAdd()." เพิ่มรายการสินค้า", ['data-url'=>Url::to(['product/create']), 'class' => 'btn btn-success btn-sm', 'id'=>'modal-addbtn-products']). ' ' .
		      Html::button(SDHtml::getBtnDelete()." ลบรายการสินค้า", ['data-url'=>Url::to(['product/deletes']), 'class' => 'btn btn-danger btn-sm', 'id'=>'modal-delbtn-products', 'disabled'=>false])
             ?>
         </div>
    </div>
<div class="box-body">    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php  Pjax::begin(['id'=>'products-grid-pjax']);?>
    <?= GridView::widget([
    'id' => 'products-grid',
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
        'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => [
                'class' => 'selectionProductIds'
            ],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions' => ['style'=>'width:40px;text-align: center;'],
	        ],
            [
                'header'=>'ลำดับ',
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions' => ['style'=>'width:60px;text-align: center;'],
            ],
            [
                'label' => 'image',
                'format' => 'raw',
                'value' => function($model){
	                $storageUrl = isset(Yii::$app->params['storageUrl'])?Yii::$app->params['storageUrl']:'';
	                if($model->image){
	                    $fileName = "{$storageUrl}/uploads/{$model->image}";
                    }else{
                        $fileName = "{$storageUrl}/uploads/noimage.png";
	                }
                    return Html::img($fileName,['class'=>'img img-responsive','style'=>'width:50px;']);
                }
            ],
            'name',
            [
                'attribute' => 'price',
                'value' => function($model){
                    if($model->price){
                        return number_format($model->price);
                    }
                }
            ],
            [
                'attribute' => 'create_by',
                'value' => function($model){
                    if($model->create_by){
                        return \common\modules\user\classes\CNUserFunc::getFullNameByUserId($model->create_by);
                    }
                }
            ],
            [
                'attribute' => 'create_date',
                'value' => function($model){
                    if($model->create_date){
                        return  \appxq\sdii\utils\SDdate::mysql2phpDateTime($model->create_date);
                    }
                }
            ],
	    [
		'class' => 'appxq\sdii\widgets\ActionColumn',
		'contentOptions' => ['style'=>'width:180px;text-align: center;'],
		'template' => '{update} {delete}',
                'buttons'=>[
                    'update'=>function($url, $model){
                        return Html::a('<span class="fa fa-pencil"></span> '.Yii::t('app', 'Update'),
                                    yii\helpers\Url::to(['product/update?id='.$model->id]), [
                                    'title' => Yii::t('app', 'Update'),
                                    'class' => 'btn btn-primary btn-xs',
                                    'data-action'=>'update',
                                    'data-pjax'=>0
                        ]);
                    },
                    'delete' => function ($url, $model) {                         
                        return Html::a('<span class="fa fa-trash"></span> '.Yii::t('app', 'Delete'), 
                                yii\helpers\Url::to(['product/delete?id='.$model->id]), [
                                'title' => Yii::t('app', 'Delete'),
                                'class' => 'btn btn-danger btn-xs',
                                'data-confirm' => Yii::t('chanpan', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-action' => 'delete',
                                'data-pjax'=>0
                        ]);
                            
                        
                    },
                ]
	    ],
        ],
    ]); ?>
    <?php  Pjax::end();?>

</div>
</div>
<?=  ModalForm::widget([
    'id' => 'modal-products',
    //'size'=>'modal-lg',
]);
?>

<?php  \richardfan\widget\JSRegister::begin([
    //'key' => 'bootstrap-modal',
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
// JS script
$('#modal-addbtn-products').on('click', function() {
    location.href = $(this).attr('data-url');
    // modalProduct($(this).attr('data-url'));
});

$('#modal-delbtn-products').on('click', function() {
    selectionProductGrid($(this).attr('data-url'));
});

$('#products-grid-pjax').on('click', '.select-on-check-all', function() {
    window.setTimeout(function() {
	var key = $('#products-grid').yiiGridView('getSelectedRows');
	disabledProductBtn(key.length);
    },100);
});

$('.selectionCoreOptionIds').on('click',function() {
    var key = $('input:checked[class=\"'+$(this).attr('class')+'\"]');
    disabledProductBtn(key.length);
});

$('#products-grid-pjax').on('dblclick', 'tbody tr', function() {
    var id = $(this).attr('data-key');
    location.href = '<?= Url::to(['product/update', 'id'=>''])?>'+id;
});	

$('#products-grid-pjax').on('click', 'tbody tr td a', function() {
    var url = $(this).attr('href');
    var action = $(this).attr('data-action');

    if(action === 'update' || action === 'view') {
	// modalProduct(url);
        location.href = url;
    } else if(action === 'delete') {
	yii.confirm('<?= Yii::t('chanpan', 'Are you sure you want to delete this item?')?>', function() {
	    $.post(
		url
	    ).done(function(result) {
		if(result.status == 'success') {
            swal({
                title: result.status,
                text: result.message,
                type: result.status,
                timer: 2000
            });
		    $.pjax.reload({container:'#products-grid-pjax'});
		} else {
            swal({
                title: result.status,
                text: result.message,
                type: result.status,
                timer: 2000
            });
		}
	    }).fail(function() {
		<?= SDNoty::show("'" . SDHtml::getMsgError() . "Server Error'", '"error"')?>
		console.log('server error');
	    });
	});
    }
    return false;
});

function disabledProductBtn(num) {
    if(num>0) {
	$('#modal-delbtn-products').attr('disabled', false);
    } else {
	$('#modal-delbtn-products').attr('disabled', true);
    }
}

function selectionProductGrid(url) {
    yii.confirm('<?= Yii::t('chanpan', 'Are you sure you want to delete these items?')?>', function() {
	$.ajax({
	    method: 'POST',
	    url: url,
	    data: $('.selectionProductIds:checked[name=\"selection[]\"]').serialize(),
	    dataType: 'JSON',
	    success: function(result, textStatus) {
		if(result.status == 'success') {
            swal({
                title: result.status,
                text: result.message,
                type: result.status,
                timer: 2000
            });
		    $.pjax.reload({container:'#products-grid-pjax'});
		} else {
            swal({
                title: result.status,
                text: result.message,
                type: result.status,
                timer: 2000
            });
		}
	    }
	});
    });
}

function modalProduct(url) {
    $('#modal-products .modal-content').html('<div class=\"sdloader \"><i class=\"sdloader-icon\"></i></div>');
    $('#modal-products').modal('show')
    .find('.modal-content')
    .load(url);
}
</script>
<?php  \richardfan\widget\JSRegister::end(); ?>