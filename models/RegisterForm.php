<?php
namespace app\models;

    use Yii;
    use yii\base\Model;

    /**
     * Register form
     */
class RegisterForm extends Model
{

    public $username;
    public $password;
    public $organization_id;
    public $position;
    public $imageFile;
    public $documentFiles;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['organization_id', 'required'],
            ['organization_id', 'integer'],
            ['position', 'required'],
            ['imageFile', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ['documentFiles', 'file', 'skipOnEmpty' => true, 'extensions' => 'doc, docx, xls, xlsx', 'maxFiles' => 4],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->organization_id = $this->organization_id;
        $user->position = $this->position;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

}