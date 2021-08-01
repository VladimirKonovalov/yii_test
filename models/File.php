<?php


namespace app\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $path
 * @property integer $user_id
 */
class File extends ActiveRecord
{
    public static function tableName()
    {
        return 'file';
    }

    public function rules()
    {
        return [
            [['path'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'path' => 'Путь к файлу',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}