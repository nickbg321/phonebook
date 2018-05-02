<?php

namespace app\tests\unit\models\domain\core;

use app\models\data\repository\UserRepository;
use app\models\data\UserData;
use app\models\domain\core\User;
use Codeception\Specify;
use Codeception\Test\Unit;
use Prophecy\Argument;
use Yii;
use yii\base\NotSupportedException;

class UserTest extends Unit
{
    use Specify;

    public function testFindIdentity()
    {
        $this->specify('Identity is correctly returned if a user is found.', function () {
            $this->setFindIdentityRepoMock();

            $user = User::findIdentity(1);

            expect($user)->isInstanceOf(User::class);
            expect($user->id)->equals(1);
            expect($user->username)->equals('test');
            expect($user->passwordHash)->equals('$2y$13$t5SJklUWk6Pc5AGUqmb9X.D5FZ8jxV2c4skfA3rLteyUho2.vZhW2');
            expect($user->authKey)->equals('test auth key');
        });

        $this->specify('Identity is null if no user is found.', function () {
            $this->setFindIdentityRepoMock(true);
            $user = User::findIdentity(1);
            expect($user)->null();
        });
    }

    public function testFindByUsername()
    {
        $this->specify('Identity is correctly returned if a user is found.', function () {
            $this->setFindByUsernameRepoMock();

            $user = User::findByUsername('test');

            expect($user)->isInstanceOf(User::class);
            expect($user->id)->equals(1);
            expect($user->username)->equals('test');
            expect($user->passwordHash)->equals('$2y$13$t5SJklUWk6Pc5AGUqmb9X.D5FZ8jxV2c4skfA3rLteyUho2.vZhW2');
            expect($user->authKey)->equals('test auth key');
        });

        $this->specify('Identity is null if no user is found.', function () {
            $this->setFindByUsernameRepoMock(true);
            $user = User::findByUsername('test');
            expect($user)->null();
        });
    }

    public function testFindIdentityByAccessToken()
    {
        $this->specify('NotSupportedException is thrown.', function () {
            User::findIdentityByAccessToken('test');
        }, ['throws' => NotSupportedException::class]);
    }

    public function testGetId()
    {
        $this->specify('Correct ID is returned.', function () {
            $this->setFindIdentityRepoMock();
            $user = User::findIdentity(1);
            expect($user->getId())->equals(1);
        });
    }

    public function testGetAuthKey()
    {
        $this->specify('Correct auth key is returned', function () {
            $this->setFindIdentityRepoMock();
            $user = User::findIdentity(1);
            expect($user->getAuthKey())->equals('test auth key');
        });
    }

    public function testValidateAuthKey()
    {
        $this->setFindIdentityRepoMock();
        $user = User::findIdentity(1);

        $this->specify('Valid auth key validation validation passes.', function () use ($user) {
            expect($user->validateAuthKey('test auth key'))->true();
        });

        $this->specify('Invalid auth key is rejected.', function () use ($user) {
            expect($user->validateAuthKey('invalid'))->false();
        });
    }

    public function testValidatePassword()
    {
        $this->setFindIdentityRepoMock();
        $user = User::findIdentity(1);

        $this->specify('Valid password validation validation passes.', function () use ($user) {
            expect($user->validatePassword('admin'))->true();
        });

        $this->specify('Invalid password is rejected.', function () use ($user) {
            expect($user->validatePassword('invalid'))->false();
        });
    }

    private function setFindByUsernameRepoMock($returnNull = false)
    {
        $prophecy = $this->prophesize(UserRepository::class);
        $prophecy->getByUsername('test')
            ->shouldBeCalledTimes(1)
            ->willReturn($returnNull ? null : $this->getUserDataMock());

        Yii::$container->set(UserRepository::class, $prophecy->reveal());
    }

    private function setFindIdentityRepoMock($returnNull = false)
    {
        $prophecy = $this->prophesize(UserRepository::class);
        $prophecy->getById(1)->shouldBeCalledTimes(1)->willReturn($returnNull ? null : $this->getUserDataMock());

        Yii::$container->set(UserRepository::class, $prophecy->reveal());
    }

    private function getUserDataMock()
    {
        $prophecy = $this->prophesize(UserData::class);
        $prophecy->hasAttribute(Argument::any())->willReturn(true);
        $prophecy->id = 1;
        $prophecy->username = 'test';
        $prophecy->password_hash = '$2y$13$t5SJklUWk6Pc5AGUqmb9X.D5FZ8jxV2c4skfA3rLteyUho2.vZhW2';
        $prophecy->auth_key = 'test auth key';

        return $prophecy->reveal();
    }
}
