<?php

namespace app\tests\unit\models\form;

use app\models\data\ContactData;
use app\models\data\repository\ContactRepository;
use app\models\form\ContactForm;
use Codeception\Specify;
use Codeception\Test\Unit;
use Prophecy\Argument;
use Yii;

class ContactFormTest extends Unit
{
    use Specify;

    public function testValidate()
    {
        /** @var ContactForm $contactForm */
        $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactDataMock()]);

        $this->specify('Valid phone number passes validation.', function () use ($contactForm) {
            $contactForm->phoneNumber = '+35988888';
            $contactForm->validate('phoneNumber');

            expect($contactForm->getErrors())->isEmpty();
        });

        $this->specify('Invalid phone number is rejected.', function () use ($contactForm) {
            $contactForm->phoneNumber = '343434';
            $contactForm->validate('phoneNumber');

            expect($contactForm->getErrors())->notEmpty();
        });

        $this->specify('Duplicate records do not pass validation.', function () use ($contactForm) {
            $this->setContactRepositoryMock();

            $contactForm->firstName = 'first';
            $contactForm->lastName = 'last';
            $contactForm->phoneNumber = '+33300000';
            $contactForm->validate('firstName');

            expect($contactForm->getErrors())->notEmpty();
        });

        $this->specify('Current record is not detected as duplicate.', function () use ($contactForm) {
            $this->setContactRepositoryMock(true, 1);

            $contactForm->firstName = 'first';
            $contactForm->lastName = 'last';
            $contactForm->phoneNumber = '+33300000';
            $contactForm->validate('firstName');

            expect($contactForm->getErrors())->isEmpty();
        });

        $this->specify('New record is not detected as duplicate', function () use ($contactForm) {
            $this->setContactRepositoryMock(false);

            $contactForm->firstName = 'first';
            $contactForm->lastName = 'last';
            $contactForm->phoneNumber = '+33300000';
            $contactForm->validate('firstName');

            expect($contactForm->getErrors())->isEmpty();
        });
    }

    public function testInit()
    {
        $this->specify('Form is correctly initialised for an existing record.', function () {
            /** @var ContactForm $contactForm */
            $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactDataMock()]);

            expect($contactForm->firstName)->equals('first');
            expect($contactForm->lastName)->equals('last');
            expect($contactForm->phoneNumber)->equals('+359000000');
            expect($contactForm->note)->equals('note');
        });

        $this->specify('Form is correctly initialised for a new record.', function () {
            /** @var ContactForm $contactForm */
            $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactDataNewMock()]);

            expect($contactForm->firstName)->null();
            expect($contactForm->lastName)->null();
            expect($contactForm->phoneNumber)->null();
            expect($contactForm->note)->null();
        });
    }

    public function testSave()
    {
        $this->specify('Form is correctly saved.', function () {
            /** @var ContactForm $contactForm */
            $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactDataMock(true)]);
            $contactForm->firstName = 'new first';
            $contactForm->lastName = 'new last';
            $contactForm->phoneNumber = '+33300000';
            $contactForm->note = 'new note';
            $contactForm->save();
        });

        $this->specify('Exception is thrown if saving fails.', function () {
            /** @var ContactForm $contactForm */
            $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactDataMock(true, false)]);
            $contactForm->firstName = 'new first';
            $contactForm->lastName = 'new last';
            $contactForm->phoneNumber = '+33300000';
            $contactForm->note = 'new note';
            $contactForm->save();
        }, ['throws' => \Exception::class]);
    }

    public function testGetContactData()
    {
        $this->specify('ContactData is correctly returned.', function () {
            /** @var ContactForm $contactForm */
            $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactDataMock()]);
            $contactData = $contactForm->getContactData();

            expect($contactData)->isInstanceOf(ContactData::class);
            expect($contactData->first_name)->equals('first');
            expect($contactData->last_name)->equals('last');
            expect($contactData->phone_number)->equals('+359000000');
            expect($contactData->note)->equals('note');
        });
    }

    private function getContactDataMock($shouldSave = false, $saveWillReturn = true)
    {
        $prophecy = $this->prophesize(ContactData::class);
        $prophecy->hasAttribute(Argument::any())->willReturn(true);
        $prophecy->id = 1;
        $prophecy->isNewRecord = false;
        $prophecy->first_name = 'first';
        $prophecy->last_name = 'last';
        $prophecy->phone_number = '+359000000';
        $prophecy->note = 'note';

        if ($shouldSave) {
            $prophecy->setAttributes([
                'first_name' => 'new first',
                'last_name' => 'new last',
                'phone_number' => '+33300000',
                'note' => 'new note',
            ])->shouldBeCalledTimes(1)->willReturn(true);

            $prophecy->save()->shouldBeCalledTimes(1)->willReturn($saveWillReturn);
        }

        return $prophecy->reveal();
    }

    private function getContactDataNewMock()
    {
        $prophecy = $this->prophesize(ContactData::class);
        $prophecy->hasAttribute(Argument::any())->willReturn(true);
        $prophecy->isNewRecord = true;

        return $prophecy->reveal();
    }

    private function setContactRepositoryMock($hasResult = true, $contactId = 2)
    {
        $contactDataMock = $this->prophesize(ContactData::class);
        $contactDataMock->hasAttribute(Argument::any())->willReturn(true);
        $contactDataMock->id = $contactId;
        $contactDataMock->first_name = 'first';
        $contactDataMock->last_name = 'last';
        $contactDataMock->phone_number = '+359000000';
        $contactDataMock->note = 'note';

        $repoMock = $this->prophesize(ContactRepository::class);
        $repoMock->getOneWithConditions([
            'first_name' => 'first',
            'last_name' => 'last',
            'phone_number' => '+33300000',
        ])->shouldBeCalledTimes(1)->willReturn($hasResult ? $contactDataMock->reveal() : null);

        Yii::$container->set(ContactRepository::class, $repoMock->reveal());
    }
}
