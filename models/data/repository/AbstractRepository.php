<?php

namespace app\models\data\repository;

use yii\db\ActiveRecord;
use Yii;

/**
 * Contains methods common for all repositories, like getting a record by ID.
 *
 * Class AbstractRepository
 * @package app\models\data\repository
 */
abstract class AbstractRepository
{
    /**
     * Returns the data model name which it tries to guess based on the repository class name.
     *
     * @return ActiveRecord|null
     */
    private function getDataModel(): string
    {
        $nameChunks = explode('\\', get_called_class());
        $modelName = str_replace('Repository', '', end($nameChunks)) . 'Data';

        return Yii::$app->params['dataModelNamespace'] . '\\' . $modelName;
    }

    /**
     * Returns a single ActiveRecord entry for a given ID.
     *
     * @param $entityId
     * @return null|ActiveRecord
     */
    public function getById($entityId): ?ActiveRecord
    {
        return static::getDataModel()::findOne($entityId);
    }

    /**
     * Returns a single for given conditions.
     *
     * @param array $conditions
     * @return null|ActiveRecord
     */
    public function getOneWithConditions(array $conditions): ?ActiveRecord
    {
        return static::getDataModel()::find()
            ->where($conditions)
            ->one();
    }
}
