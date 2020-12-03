<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nama;
    public $nip;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'nama', 'nip', 'email', 'password'], 'required', 'message' => '{attribute} tidak boleh kosong'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Username ini sudah dipakai.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['nama', 'trim'],
            ['nama', 'required'],
            ['nama', 'string', 'min' => 2, 'max' => 64],

            ['nip', 'trim'],
            ['nip', 'required'],
            ['nip', 'number', 'message'=> '{attribute} harus berupa angka'],
            ['nip', 'number', 'min' => 2],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Email ini sudah dipakai.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],


        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->nama = $this->nama;
        $user->nip = $this->nip;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        // the following three lines were added:
        $auth       = \Yii::$app->authManager;
        $authorRole = $auth->getRole('dosen');
        $auth->assign($authorRole, $user->getId());

        return $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
