<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name
 *
 * @property User[] $users
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор организации',
            'name' => 'Название организации',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['organization_id' => 'id']);
    }

//    /**
//     * @inheritdoc
//     * @return CountryQuery the active query used by this AR class.
//     */
//    public static function find()
//    {
//        return new CountryQuery(get_called_class());
//    }

    public static function getList()
    {
        return ArrayHelper::map(
            static::find()->select(['id', 'name'])->all(),
            'id',
            'name');
    }
}
