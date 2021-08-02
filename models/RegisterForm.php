<?php
namespace app\models;

    use Yii;
    use yii\base\Model;
    use yii\helpers\Url;
    use yii\web\UploadedFile;

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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Пользователь',
            'password' => 'Пароль',
            'organization_id' => 'Организация',
            'position' => 'Должность',
            'imageFile' => 'Фото',
            'documentFiles' => 'Документы'
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

    public function upload($prefix, $user_id)
    {
        if ($this->validate()) {
            // image
            // save file
            $filename = 'uploads/images/'. $prefix. '_' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($filename);
            // add db record
            $modelFile = new File();
            $absPath = Url::home(true);
            $modelFile->path = $absPath.$filename;
            $modelFile->user_id = $user_id;
            $modelFile->save();

            //documents
            // save files cycle
            foreach ($this->documentFiles as $index => $file) {
                $modelDocument = new Document();
                $filename = 'uploads/files/'. $prefix. '_' . $index . '_'. $file->baseName . '.' . $file->extension;
                $file->saveAs($filename);
                // add db record
                $modelDocument->name = $file->baseName . '.' . $file->extension;
                $modelDocument->path = $prefix. '_' . $index . '_'. $file->baseName . '.' . $file->extension;
                $modelDocument->user_id = $user_id;
                $modelDocument->save();
            }

            return true;
        } else {
            return false;
        }
    }
}