<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "publisher".
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 *
 * @property Game[] $games
 */
class Publisher extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'publisher';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['address', 'name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Publisher',
        ];
    }

    /**
     * Gets query for [[Games]].
     *
     * @return ActiveQuery
     */
    public function getGames(): ActiveQuery
    {
        return $this->hasMany(Game::class, ['publisher_id' => 'id']);
    }


}
