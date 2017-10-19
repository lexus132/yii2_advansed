<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">

        <?= Wkii\AdminLTE\Widgets\Menu::widget([
            'items' => [
                ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                ['label' => 'News', 'url' => ['news/index'], 'icon' =>'fa-gavel', 'pjax'=>true],
                ['label' => 'Create', 'url' => ['news/create'], 'icon' =>'fa-gavel', 'pjax'=>true],
//                ['label' => 'Parser', 'url' => ['news/parser'], 'icon' =>'fa-gavel', 'pjax'=>true],
//                ['label' => 'Gii', 'url' => ['/gii'], 'icon' =>'fa-gavel', 'pjax'=>true],
//                ['label' => 'Debug', 'url' => ['/debug'], 'icon' => 'fa-bug'],
                ['label' => 'Logout', 'url' => ['site/logout'], 'visible' => !Yii::$app->user->isGuest, 'icon' => 'fa-sign-out'],
            ],
        ]) ?>

    </section>
    <!-- /.sidebar -->
</aside>
