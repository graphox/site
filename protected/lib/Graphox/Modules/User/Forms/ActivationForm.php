<?php

namespace Graphox\Modules\User\Forms;

use \Yii;
use \CHtml;

class ActivationForm extends \CFormModel
{

    public $key;

    public function rules()
    {
        return array(
            array(
                'key',
                'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'key' => Yii::t('user.activate',
                    'The activation key you received by mail.'),
        );
    }

    public function getFormConfig()
    {
        return array(
            'title' => 'Activate your account',
            'showErrorSummary' => true,
            'action' => array(
                '/user/auth/activate'),
            'elements' => array(
                'key' => array(
                    'type' => 'text',
                    'maxlength' => 32,
                    'hint' => Yii::t('user.activate',
                            'The activation key you received by email.'),
                    'placeholder' => Yii::t('user.activate', 'Your key'),
                    'class' => 'input-large',
                    'append' => '<i class="icon-user"></i>',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'layoutType' => 'primary',
                    'label' => Yii::t('user.activate', 'Log in'),
                )
            ),
        );
    }

    /**
     * Validate the username/email - password combination
     */
    public function activate($validate = true)
    {
        if ($validate && !$this->validate())
        {
            return false;
        }

        $emailRepository = Yii::app()->neo4j->getRepository('\Graphox\Modules\User\Email');

        /**
         * @var \Graphox\Modules\User\Email
         */
        $email = $emailRepository->findOneByActivationKey($this->key);

        if ($email !== null)
        {
            $email->setIsActivated(true);
            $email->setActivationKey(null);

            Yii::app()->neo4j->persist($email);
            Yii::app()->neo4j->flush();

            Yii::log('User activated email ' . $email->getEmail() . ' [' . $email->getId() . '].',
                    \CLogger::LEVEL_INFO);

            return true;
        }

        Yii::log('User tried to register invalid key', \CLogger::LEVEL_WARNING);
        $this->addError('key', 'Invalid key.');

        return false;
    }

}

