<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "genre".
 *
 * @property int $id
 * @property string $name
 *
 * @property Game[] $games
 */
class Genre extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Genre',
        ];
    }

    /**
     * Gets query for [[Games]].
     *
     * @return ActiveQuery
     */
    public function getGames(): ActiveQuery
    {
        return $this->hasMany(Game::class, ['genre_id' => 'id']);
    }

}
