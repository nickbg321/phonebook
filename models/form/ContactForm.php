<?php

namespace app\models\form;

use app\models\data\ContactData;
use app\components\validators\PhoneNumberValidator;
use app\models\data\repository\ContactRepository;
use yii\base\Model;
use Yii;

/**
 * Class ContactForm
 * @package app\models\form
 */
class ContactForm extends Model
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $note;

    /**
     * @var ContactData
     */
    private $contactData;

    /**
     * ContactForm constructor.
     * @param ContactData $contactData
     * @param array $config
     */
    public function __construct(ContactData $contactData, array $config = [])
    {
        $this->contactData = $contactData;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['firstName', 'lastName', 'phoneNumber'], 'required'],
            [['firstName', 'lastName'], 'trim'],
            [['note'], 'string'],
            [['firstName', 'lastName'], 'string', 'max' => 35],
            [['phoneNumber'], 'string', 'max' => 15],
            [['phoneNumber'], PhoneNumberValidator::class],
            [['firstName'], 'validateUniqueContact'],
        ];
    }

    /**
     * Sets the form attributes when editing an existing contact record.
     */
    public function init()
    {
        if (!$this->contactData->isNewRecord) {
            $this->firstName = $this->contactData->first_name;
            $this->lastName = $this->contactData->last_name;
            $this->phoneNumber = $this->contactData->phone_number;
            $this->note = $this->contactData->note;
        }
    }

    /**
     * Handles the contact saving process.
     * Sets ContactData attributes and attempts to save the data model.
     *
     * @throws \Exception
     */
    public function save(): void
    {
        $this->contactData->setAttributes([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone_number' => $this->phoneNumber,
            'note' => $this->note,
        ]);

        if (!$this->contactData->save()) {
            throw new \Exception('Could not save contact data.');
        }
    }

    /**
     * Validates that the provided combination of first name, last name and phone number is unique.
     *
     * @param string $attribute
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function validateUniqueContact(string $attribute)
    {
        /** @var ContactData $contactData */
        $contactData = Yii::$container->get(ContactRepository::class)->getOneWithConditions([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone_number' => $this->phoneNumber,
        ]);

        if ($contactData && $contactData->id != $this->contactData->id) {
            $this->addError($attribute, 'Contact already exists.');
        }
    }

    /**
     * @return ContactData
     */
    public function getContactData(): ContactData
    {
        return $this->contactData;
    }
}
