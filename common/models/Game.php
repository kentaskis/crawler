<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property string $name
 * @property string|null $icon
 * @property int $genre_id
 * @property int $publisher_id
 * @property float $rate
 * @property int|null $installs
 * @property string|null $url
 *
 * @property Genre $genre
 * @property Publisher $publisher
 */
class Game extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'genre_id', 'publisher_id', 'rate'], 'required'],
            [['genre_id', 'publisher_id', 'installs'], 'integer'],
            [['rate'], 'number'],
            [['name', 'icon', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
            'genre_id' => 'Genre',
            'publisher_id' => 'Publisher',
            'rate' => 'Rate',
            'installs' => 'Installs',
            'address' => 'Address',
            'url' => 'Url',
        ];
    }

    /**
     * Gets query for [[Genre]].
     *
     * @return ActiveQuery
     */
    public function getGenre(): ActiveQuery
    {
        return $this->hasOne(Genre::class, ['id' => 'genre_id']);
    }

    /**
     * Gets query for [[Publisher]].
     *
     * @return ActiveQuery
     */
    public function getPublisher(): ActiveQuery
    {
        return $this->hasOne(Publisher::class, ['id' => 'publisher_id']);
    }
}
