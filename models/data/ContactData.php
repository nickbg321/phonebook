<?php

namespace app\models\data;

use app\components\validators\PhoneNumberValidator;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone_number
 * @property string $note
 */
class ContactData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['first_name', 'last_name', 'phone_number'], 'required'],
            [['note'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 35],
            [['phone_number'], 'string', 'max' => 15],
            [['phone_number'], PhoneNumberValidator::class],
            [['first_name'], 'unique', 'targetAttribute' => ['first_name', 'last_name', 'phone_number']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone_number' => 'Phone Number',
            'note' => 'Note',
        ];
    }
}
