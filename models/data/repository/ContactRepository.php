<?php

namespace app\models\data\repository;

use app\models\data\ContactData;
use yii\db\ActiveQuery;

/**
 * Class ContactRepository
 * @package app\models\data\repository
 */
class ContactRepository extends AbstractRepository
{
    /**
     * Returns a ContactData ActiveQuery.
     *
     * @return ActiveQuery
     */
    public function getQuery(): ActiveQuery
    {
        return ContactData::find();
    }
}
