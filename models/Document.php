<?php


namespace app\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property string $path
 * @property string $name
 * @property integer $user_id
 */
class Document extends ActiveRecord
{
    public static function tableName()
    {
        return 'document';
    }

    public function rules()
    {
        return [
            [['path'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 255],
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


