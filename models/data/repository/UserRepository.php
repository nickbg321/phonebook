<?php

namespace app\models\data\repository;

use app\models\data\UserData;

/**
 * Class UserRepository
 * @package app\models\data\repository
 */
class UserRepository extends AbstractRepository
{
    /**
     * Returns a single UserData entry for given a access token.
     *
     * @param string $token
     * @return UserData|null
     */
    public function getByAccessToken(string $token): ?UserData
    {
        return UserData::findOne(['access_token' => $token]);
    }

    /**
     * Returns a single UserData entry for a given username.
     *
     * @param string $username
     * @return UserData|null
     */
    public function getByUsername(string $username): ?UserData
    {
        return UserData::findOne(['username' => $username]);
    }
}
