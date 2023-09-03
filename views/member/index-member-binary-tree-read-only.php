<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Binary Tree';
$this->params['breadcrumbs'][] = $this->title;

echo \app\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'options' => [
        'title' => $this->title
    ],
]) ?>

<?php
/* 
echo '<pre>'; var_dump($dataChartBinary); echo '</pre>'; 
*/ 
?>

<div class="row mb-4">
    <div class="container-fluid">
        <div class="dt-button-wrapper">
            
        </div>

        <div class="downline-index card card-nav-tabs">
            <div class="card-header card-header-warning text-left" style="padding:unset">
            </div>
            <div class="card-body" style="height: 600px; background-color: #fff" id="chart-binary">
                <div style="float:right; position: absolute; right: 15px">
                    <a id="ic_index" style="display: inline-block" class="btn btn-sm btn-outline-primary" href="<?= Url::to([
                        '/member/index-admin-distributor',
                    ]) ?>">
                        <i class="ti-list mr-2" style="font-size: 16px; vertical-align: inherit"></i> Index
                    </a>
                    <a id="ic_refresh" style="display: inline-block" class="btn btn-sm btn-outline-primary" href="<?= Url::to([
                            '/member/index-member-binary-tree-read-only',
                            'id_member_binary' => @$idMemberRoot,
                            'id_member_root' => @$idMemberRoot
                    ]) ?>">
                        <i class="ti-reload mr-2" style="font-size: 16px; vertical-align: inherit"></i> Refresh
                    </a>
                    <a id="ic_fullscreen_binary" style="display: inline-block" class="btn btn-sm btn-outline-primary" href="#" onclick="setToggleFullscreenBinary()">
                        <i class="ti-fullscreen mr-2" style="font-size: 16px; vertical-align: inherit"></i> Fullscreen
                    </a>
                    <a id="ic_fullscreen_exit_binary" style="display: none" class="btn btn-sm btn-outline-primary" href="#" onclick="setToggleFullscreenBinary()">
                        <i class="ti-close mr-2" style="font-size: 16px; vertical-align: inherit"></i> Close
                    </a>
                </div>
                <?= $this->render('_binary_tree_svg', [
                    'dataChartBinary' => $dataChartBinary
                ]) ?>
            </div>
        </div>
    </div>
</div>



<?php

$urlCreate = Url::to(['member/create-member-downline']);
$urlData = Url::to(['member/index-member-binary-tree-read-only']);

$script = <<< JS
    var toggleFullscreen = false;
    var lastContainerHeight;
    var lastSvgAttributes = {};
    
    const setToggleFullscreenBinary = () => {
    
        toggleFullscreen = !toggleFullscreen;
    
        if (toggleFullscreen) {
            $('#ic_fullscreen_exit_binary').css({"display": "inline-block"});
            $('#ic_fullscreen_binary').css({"display": "none"});
        } else {
            $('#ic_fullscreen_exit_binary').css({"display": "none"});
            $('#ic_fullscreen_binary').css({"display": "inline-block"});
        }
        // console.log('toggle fullscreen : ' + toggleFullscreen);
        // console.log($('#chart-binary svg').attr('viewBox'));
    
        if (toggleFullscreen) {            
            // lastContainerHeight = $('.svg-chart-container').height();
            // $('.svg-chart-container').height('100%');
            lastSvgAttributes = {
                viewBox: $('#chart-binary svg').attr('viewBox'),
                width: $('#chart-binary svg').width()
            }
            // lastViewBox = $('#chart-binary svg').attr('viewBox');
            $('#chart-binary svg').width('100%');
            $('#chart-binary svg').attr('viewBox', '0 -50 1000 600')
            document.querySelector("#chart-binary").requestFullscreen();
        } else {
            // $('.svg-chart-container').height(lastContainerHeight);
            $('#chart-binary svg').attr('viewBox', lastSvgAttributes.viewBox);
            $('#chart-binary svg').width(lastSvgAttributes.width);
            document.exitFullscreen();
        }
    }
    
    $('g.node').each(function() {
        $(this).click(function() {
            queryAsRoot($(this));
        });
    });
    
    /** interaction on svg */
    const queryAsRoot = (elem) => {
        const id = $(elem).attr('id');
        const idUpline = $(elem).attr('parent-id');
        const idGroup = $(elem).attr('group-id');
        const posisi = $(elem).attr('position');
        if (idUpline === undefined || idUpline === '') {
            return false;
        }
        if (id === undefined || id === '') {
            return false;
        } 
        location.href = "{$urlData}" + "?id_member_binary=" + id + "&id_member_root=" + "{$idMemberRoot}";        
    }
    
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

JS;

$this->registerJs($script, \yii\web\View::POS_END);


$style = <<<CSS
    #chart-binary-svg {
        margin-top: 80px;
    }

    .bt-node_content .btn {
        display: none !important;        
    }
CSS;

$this->registerCss($style);

?>