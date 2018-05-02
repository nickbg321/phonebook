<?php

namespace app\components\validators;

use yii\validators\Validator;

/**
 * Validates that the provided phone number adheres to the E.164 international phone number standard.
 *
 * The phone number should begin with a plus symbol, should contain only numeric characters
 * after the preceding plus symbol and should have a maximum length of 15 characters.
 *
 * Class PhoneNumberValidator
 * @package app\components\validators
 * @link https://www.twilio.com/docs/glossary/what-e164
 */
class PhoneNumberValidator extends Validator
{
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if (empty(preg_match('/^\+[1-9]\d{1,14}$/', $model->$attribute))) {
            $this->addError($model, $attribute,
                'Phone number must adhere to the E.164 international phone number standard.');
        }
    }
}
