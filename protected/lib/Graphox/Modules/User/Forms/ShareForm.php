<?php

namespace Graphox\Modules\User\Forms;

use \Yii;
use \CHtml;

class ShareForm extends \CFormModel
{

    public $source;

    public function rules()
    {
        return array(
            array(
                'source',
                'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'source' => Yii::t('user.share', 'Share'),
        );
    }

    public function getFormConfig()
    {
        return array(
            'title' => 'Share',
            'showErrorSummary' => true,
            'action' => array(
                '/user/timeline/share'),
            'elements' => array(
                'source' => array(
                    'type' => 'markdowneditor',
                    'hint' => Yii::t('user.share',
                            'What would you like to share?'),
                    'placeholder' => Yii::t('user.share', 'Today I ...'),
                    'class' => 'input-large',
                    'id' => 'Share-markdown'
                )
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'layoutType' => 'primary',
                    'label' => Yii::t('user.share', 'Share!'),
                ),
            ),
        );
    }

}

