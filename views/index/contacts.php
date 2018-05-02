<?php

/** @var $this yii\web\View */
/** @var $searchModel \app\models\search\ContactSearch */
/** @var $dataProvider \yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Phonebook';
?>

<div class="row">
    <div class="col-md-12">
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add new contact',
            ['id' => 'add-btn', 'value' => Url::to('create'), 'class' => 'btn btn-primary btn-md add-contact-btn']); ?>
    </div>
</div>

<?php
Pjax::begin();

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'first_name',
        'last_name',
        'phone_number',
        'note',
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 150px; text-align: center;'],
            'header' => 'Actions',
            'template' => '{edit} {delete}',
            'buttons' => [
                'edit' => function ($url, $model) {
                    /** @var \app\models\data\ContactData $model */
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> Edit', Url::to(['edit', 'contactId' => $model->id]), [
                        'title' => Yii::t('app', 'Edit'),
                        'class' => 'edit-btn btn btn-primary btn-xs',
                    ]);
                },
                'delete' => function ($url, $model) {
                    /** @var \app\models\data\ContactData $model */
                    return Html::a('<span class="glyphicon glyphicon-remove"></span> Delete', Url::to(['delete', 'contactId' => $model->id]), [
                        'title' => Yii::t('app', 'Delete'),
                        'class' => 'btn btn-primary btn-xs',
                        'data' => [
                            'confirm' => 'Are you sure  you want to delete this record?',
                        ],
                    ]);
                },
            ],
        ],
    ]
]);

Pjax::end();

echo '<div id="modelContent"></div>';

$this->registerJs("
    var loading = false;

    $('#add-btn').click(function() {                  
        if (loading) {
            return false;
        }
        
        loading = true;
    
        $('#modelContent').load($(this).attr('value'), function() {
            $('.modal').modal('show');
            loading = false;
        });
    });
    
    $('body').on('click', '.edit-btn', function(e) {
        if (loading) {
            e.preventDefault();
            return false;
        }
        
        loading = true;
        e.preventDefault();
                                           
        $('#modelContent').load($(this).attr('href'), function() {
            $('.modal').modal('show');
            loading = false;
        });
    });
");
