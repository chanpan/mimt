<?php

namespace backend\components;

use appxq\sdii\utils\VarDumper;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\UnauthorizedHttpException;

class AppComponent extends Component
{

    public function init()
    {
        parent::init();
        $params = \backend\modules\core\classes\CoreQuery::getOptionsParams();
        \Yii::$app->params = \yii\helpers\ArrayHelper::merge(\Yii::$app->params, $params);
        Yii::setAlias('@storageUrl', \Yii::$app->params['storageUrl']);
        //\appxq\sdii\utils\VarDumper::dump($params);
    }

    public static function navbarLeft()
    {
        $moduleId = (isset(Yii::$app->controller->module->id) && Yii::$app->controller->module->id != 'app-backend') ? Yii::$app->controller->module->id : '';
        $controllerId = isset(Yii::$app->controller->id) ? Yii::$app->controller->id : '';
        $actionId = isset(Yii::$app->controller->action->id) ? Yii::$app->controller->action->id : '';
        $viewId = \Yii::$app->request->get('id', '');

        $navbar = [
            'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
            'items' => [
                ['label' => \Yii::t('appmenu', 'Home'), 'icon' => 'home', 'url' => ['/']],

                ['label' => \Yii::t('app', 'รายการสินค้า'), 'icon' => '', 'url' => ['/products/product/index']],
                ['label' => \Yii::t('app', 'รายการสั่งซื้อ'), 'icon' => '', 'url' => ['/products/orders/index']],
                ['label' => \Yii::t('app', 'หมวดหมู่'), 'icon' => '', 'url' => ['/products/categorys/index']],
                ['label' => \Yii::t('app', 'จัดการธนาคาร'), 'icon' => '', 'url' => ['/products/banks/index']],

                ['label' => \Yii::t('app', 'จัดการลูกค้า'), 'icon' => '', 'url' => ['/booking/members/index']],
                ['label' => \Yii::t('app', 'จัดการอบรม'), 'icon' => '', 'url' => ['/booking/booking/index']],
                [
                    'label' => Yii::t('appmenu', 'Member Management'),
                    'icon' => 'users', 'url' => ['/user/admin/index'],
                    'visible' => \Yii::$app->user->can('admin')
                ],
                ['label' => \Yii::t('app', 'ที่อยู่สำหรับจัดส่ง'), 'icon' => '', 'url' => ['/products/shippings/index']],

                ['label' => \Yii::t('appmenu', 'About'), 'icon' => 'user', 'url' => ['/site/about']],
                ['label' => \Yii::t('appmenu', 'Contact'), 'icon' => 'phone-square', 'url' => ['/site/contact']],
//                ['label' => \Yii::t('appmenu', 'Informations'), 'icon' => 'microphone', 'url' => ['/informations/index']],
//                    ['label' => \Yii::t('appmenu','Clinical Data Management'), 'icon' => '', 'url' => ['/'], 'visible' => !Yii::$app->user->isGuest],


                [
                    'label' => Yii::t('appmenu', 'System Config'),
                    'visible' => \Yii::$app->user->can('admin'),
                    'icon' => 'cog',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => Yii::t('appmenu', 'Authentication'),
                            'active' => ($moduleId == 'admin'),
                            'icon' => 'cogs',
                            'url' => '#',
                            'items' => [
                                //Assignments
                                ['label' => Yii::t('appmenu', 'Assignments'), 'icon' => 'circle-o', 'url' => ['/admin'], 'active' => ($moduleId == 'admin' && $controllerId == 'assignment'),],
                                ['label' => Yii::t('appmenu', 'Role'), 'icon' => 'circle-o', 'url' => ['/admin/role'], 'active' => ($moduleId == 'admin' && $controllerId == 'role')],
                                ['label' => Yii::t('appmenu', 'Route'), 'icon' => 'circle-o', 'url' => ['/admin/route'], 'active' => ($moduleId == 'admin' && $controllerId == 'route')],
                                ['label' => Yii::t('appmenu', 'Permission'), 'icon' => 'circle-o', 'url' => ['/admin/permission'], 'active' => ($moduleId == 'admin' && $controllerId == 'permission')],
                            ],
                        ],
                        [
                            'label' => Yii::t('appmenu', 'Tools'),
                            'icon' => 'share',
                            'url' => '#',
                            'items' => [
                                //options
                                ['label' => Yii::t('appmenu', 'Options'), 'icon' => 'cog', 'url' => ['/options'],],
                                ['label' => Yii::t('appmenu', 'Skin'), 'icon' => 'cog', 'url' => ['/skin'],],
                                ['label' => Yii::t('appmenu', 'Gii'), 'icon' => 'file-code-o', 'url' => ['/gii'],],
                                ['label' => Yii::t('appmenu', 'Debug'), 'icon' => 'dashboard', 'url' => ['/debug'],]
                            ],
                        ],
                    ],
                ],
            ],
        ];
        return $navbar;
    }

    public static function menuRight()
    {
        $firstname = isset(Yii::$app->user->identity->profile->firstname) ? Yii::$app->user->identity->profile->firstname : '';
        $lastname = isset(Yii::$app->user->identity->profile->lastname) ? Yii::$app->user->identity->profile->lastname : "";
        $fullName = "{$firstname} {$lastname}";
        $storageUrl = isset(\Yii::$app->params['storageUrl']) ? \Yii::$app->params['storageUrl'] : '';

        $img = isset(Yii::$app->user->identity->profile->avatar_path) ? Yii::$app->user->identity->profile->avatar_path : '';
        $noimage = Url::to("@web/img/noimage.png");
        if($img == ''){
            $img = $noimage;
        }else{
            $img = "{$storageUrl}/source/{$img}";
        }
        $items = [
            [
                'label' => "<img src='{$img}' class='user-image'> " . $fullName,
                'visible' => !Yii::$app->user->isGuest,
                'items' => [
                    ['label' => '<i class="fa fa-user"></i> ' . Yii::t('appmenu', 'User Profile'), 'url' => ['/user/settings/profile']],
                    '<li class="divider"></li>',
                    ['label' => '<i class="fa fa-sign-out"></i> ' . Yii::t('appmenu', 'Logout'), 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']],
                ],
            ],
            ['label' => "<i class='fa fa-sign-in'></i> " . Yii::t('appmenu', 'Sign Up'), 'url' => ['/user/register'], 'visible' => Yii::$app->user->isGuest],
            ['label' => "<i class='fa fa-sign-in'></i> " . Yii::t('appmenu', 'Login'), 'url' => ['/user/login'], 'visible' => Yii::$app->user->isGuest],
        ];
        return $items;
    }

    public static function slideToggleLeft()
    {
        return \yii\helpers\Html::a("<span class='sr-only'></span>", '#', [
            'class' => 'sidebar-toggle',
            'data-toggle' => 'push-menu',
            'role' => 'button',
            'id' => 'iconslideToggle'
        ]);
    }

    public static function slideToggleRight()
    {
        return

            \yii\helpers\Html::button("<i class='fa fa-bars'></i>", [
                'class' => 'navbar-toggle',
                'data-toggle' => 'collapse',
                'data-target' => '#cnNavbar',

            ]);

    }
}
