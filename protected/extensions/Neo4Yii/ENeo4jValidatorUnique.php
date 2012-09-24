<?php

/**
 * ENeo4jValidatorUnique
 **/
class ENeo4jValidatorUnique extends CValidator
{
    /**
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty=true;

    /**
     * @var string the ActiveRecord class attribute name that should be
     * used to look for the attribute value being validated. Defaults to null,
     * meaning using the name of the attribute being validated.
     * @see className
     */
    public $attributeName;

    /**
     * @var string the user-defined error message. The placeholders "{attribute}" and "{value}"
     * are recognized, which will be replaced with the actual attribute name and value, respectively.
     */
    public $message;

    /**
     * @var boolean whether this validation rule should be skipped if when there is already a validation
     * error for the current attribute. Defaults to true.
     * @since 1.1.1
     */
    public $skipOnError=true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object,$attribute)
    {
        $value=$object->$attribute;
        $attributeName=$this->attributeName===null?$attribute:$this->attributeName;

        if($this->allowEmpty && $this->isEmpty($value))
            return;

        $className=get_class($object);
        $objects=$className::model()->findByAttributes(
            array(
                $attributeName => $value,
            )
        );
        $n=count($objects);
        $exists=$n>0;

        if($exists)
        {
            $message=$this->message!==null?$this->message:Yii::t('yii','{attribute} "{value}" has already been taken.');
            $this->addError($object,$attribute,$message,array('{value}'=>$value));
        }
    }
}

