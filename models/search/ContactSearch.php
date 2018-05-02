<?php

namespace app\models\search;

use app\models\data\ContactData;
use app\models\data\repository\ContactRepository;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class ContactSearch
 * @package app\models\search
 */
class ContactSearch extends ContactData
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * ContactSearch constructor.
     * @param array $config
     * @param ContactRepository $contactRepository
     */
    public function __construct(ContactRepository $contactRepository, array $config = [])
    {
        $this->contactRepository = $contactRepository;

        parent::__construct($config);
    }

    /**
     * Returns an ActiveDataProvider instance with filters, pagination and sorting applied.
     *
     * @param $params
     * @return object
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function search($params)
    {
        $query = $this->contactRepository->getQuery();

        $dataProvider = Yii::$container->get(ActiveDataProvider::class, [], [
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!empty($this->first_name)) {
            $query->andFilterWhere(['like', 'first_name', $this->first_name]);
        }

        if (!empty($this->last_name)) {
            $query->andFilterWhere(['like', 'last_name', $this->last_name]);
        }

        if (!empty($this->phone_number)) {
            $query->andFilterWhere(['like', 'phone_number', $this->phone_number]);
        }

        if (!empty($this->note)) {
            $query->andFilterWhere(['like', 'note', $this->note]);
        }

        return $dataProvider;
    }
}
