<?php

namespace app\models\domain\core;

use app\models\data\UserData;
use app\models\data\repository\UserRepository;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use Yii;

/**
 * Class User
 * @package app\models\domain\core
 */
class User implements IdentityInterface
{
    /**
     * User ID
     *
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $passwordHash;

    /**
     * Authentication key used for generating "Remember me" cookies.
     *
     * @var string
     */
    public $authKey;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function findIdentity($id): ?User
    {
        /** @var UserData $userData */
        $userData = Yii::$container->get(UserRepository::class)->getById($id);

        if (!$userData) {
            return null;
        }

        return self::getIdentity($userData);
    }

    /**
     * @param string $username
     * @return User|null
     * @throws InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function findByUsername(string $username): ?User
    {
        /** @var UserData $userData */
        $userData = Yii::$container->get(UserRepository::class)->getByUsername($username);

        if (!$userData) {
            return null;
        }

        return self::getIdentity($userData);
    }

    /**
     * @inheritdoc
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): void
    {
        throw new NotSupportedException('Access token authentication is not supported.');
    }

    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Checks if the provided password matches the user's.
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    /**
     * Returns an instance of User with attributes set from the provided UserData record.
     *
     * @param UserData $userData
     * @return User
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private static function getIdentity(UserData $userData): User
    {
        /** @var User $user */
        $user = Yii::$container->get(User::class, [], [
            'id' => $userData->id,
            'username' => $userData->username,
            'passwordHash' => $userData->password_hash,
            'authKey' => $userData->auth_key,
        ]);

        return $user;
    }
}
