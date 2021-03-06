<div class="container-fluid">
    <div class="row">
        <aside class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    หมายเลขคำสั่งซื้อ <?= $model->order_id; ?>
                    <?php
                            if ($model->status == '1') {
                                echo '<label class="label label-warning">ยังไม่ชำระเงิน</label>';
                            } else if ($model->status == '2') {
                                echo '<label class="label label-success">ชำระเงินแล้ว</label>';
                            }else if ($model->status == '3') {
                                echo '<label class="label label-success">ขายสินค้าแล้ว</label>';
                            }else{
                                echo '<label class="label label-danger">ยกเลิกคำสั่งซื้อ</label>';
                            }
                            echo ' ';
                            if ($model->del_status =='' || $model->del_status == '1') {
                                echo '<label class="label label-warning">รอจัดส่ง</label>';
                            } else if ($model->del_status == '2') {
                                echo '<label class="label label-success">จัดส่งแล้ว</label>';
                            }
                    ?>
                </div>
                <div class="card-body">
                    <?php
                        $storageUrl = isset(Yii::$app->params['storageUrl']) ? Yii::$app->params['storageUrl'] : '';
                        $detail = \frontend\classes\CNOrder::getOrderDetailByOrderId($model->order_id);
                    ?>

                        <div class="table-responsive">
                            <table class="table table-borderless table-shopping-cart">
                                <thead class="text-muted">
                                <tr class="small text-uppercase">

                                    <th scope="col">รายการ</th>
                                    <th scope="col" width="120">จำนวน</th>
                                    <th scope="col" width="120">ราคา</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!$detail):?>
                                    <tr>
                                        <td colspan="3">ไม่พบข้อมูลสินค้า</td>
                                    </tr>
                                <?php endif;?>
                                <?php if ($detail): ?>
                                    <?php foreach($detail as $k=>$v):?>
                                        <?php
                                            $product = \frontend\classes\CNProduct::getProductById($v['pro_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <figure class="itemside align-items-center">
                                                    <div class="aside">
                                                        <?php

                                                        $path = $storageUrl;
                                                        $image = '';

                                                        if (!$product['image']) {
                                                            $image = "{$path}/uploads/noimage.png";
                                                        } else {
                                                            $image = "{$path}/uploads/{$product['image']}";
                                                        }
                                                        ?>
                                                        <img src="<?= $image; ?>" class="img-sm">
                                                    </div>
                                                    <figcaption class="info">
                                                        <a href="#" class="title text-dark"
                                                           data-abc="true"><?= $product['name']; ?></a>
                                                    </figcaption>
                                                </figure>
                                            </td>
                                            <td>
                                                <?= $v->qty; ?>
                                            <td>
                                                <div class="price-wrap">
                                                    <span class="price">฿<?= number_format($v['price']) ?></span>

                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>


                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <label>ยอดคำสั่งซื้อทั้งหมด: <span style="font-size: 20pt;font-weight: 100;color: #FF5722;padding-left:5px;">฿<?= number_format($model->total)?></span></label>
                        <div>
                            <a href="<?= \yii\helpers\Url::to(['/order/detail?order_id='.$v->order_id])?>" class="btn btn-default">ดูข้อมูลการสั่งซื้อ</a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>