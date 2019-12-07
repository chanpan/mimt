<?phpuse backend\modules\products\models\Categorys;use yii\helpers\Html;use yii\web\View;/* @var $this yii\web\View *//* @var $model backend\modules\products\models\Categorys */$this->title = Yii::t('app', 'รายละเอียดสินค้า');$this->params['breadcrumbs'][] = ['label' => 'รายการสินค้า', 'url' => ['/product/index']];$this->params['breadcrumbs'][] = $this->title;?><?php        $storageUrl = isset(Yii::$app->params['storageUrl'])?Yii::$app->params['storageUrl']:'';        if($product->image){            $fileName = "{$storageUrl}/uploads/{$product->image}";        }else{            $fileName = "{$storageUrl}/uploads/noimage.png";        }?><div class="card">    <div class="container-fliud">        <div class="wrapper row">            <div class="preview col-md-5">                <div class="preview-pic tab-content">                    <div class="tab-pane active" id="pic-1">                        <img src="<?= $fileName; ?>" />                    </div><!--                    <div class="tab-pane" id="pic-2"><img src="http://placekitten.com/400/252" /></div>--><!--                    <div class="tab-pane" id="pic-3"><img src="http://placekitten.com/400/252" /></div>--><!--                    <div class="tab-pane" id="pic-4"><img src="http://placekitten.com/400/252" /></div>--><!--                    <div class="tab-pane" id="pic-5"><img src="http://placekitten.com/400/252" /></div>-->                </div>                <ul class="preview-thumbnail nav nav-tabs">                    <li class="active">                        <a data-target="#pic-1" data-toggle="tab">                            <img src="<?= $fileName; ?>" />                        </a>                    </li><!--                    <li><a data-target="#pic-2" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>--><!--                    <li><a data-target="#pic-3" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>--><!--                    <li><a data-target="#pic-4" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>--><!--                    <li><a data-target="#pic-5" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>-->                </ul>            </div>            <div class="details col-md-7">                <h3 class="product-title"><?= isset($product->name)?$product->name:'' ?></h3>                <h4 class="price">ราคา: <span>฿<?= isset($product->price)?number_format($product->price, 2):'' ?></span></h4>                <div class="action">                    <button class="add-to-cart btn btn-default" type="button"><?= $this->render('_cart')?> เพิ่มในรถเข็น</button>                </div>                <hr>                <div class="product-title">                    <?= isset($product->detail)?$product->detail:'' ?>                </div>            </div>        </div>    </div></div><?= $this->render('style')?>