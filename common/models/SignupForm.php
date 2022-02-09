<?php

namespace common\models;

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

    public $re_password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este dni ya ha sido tomado.'],
            ['username', 'string', 'min' => 8, 'max' => 12],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'match', 'pattern' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'],
            ['email', 'string', 'max' => 50],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Esta dirección de correo electrónico ya se encuentra en el sistema.'],

            ['password', 'required'],
            // ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['re_password', 'required'],
            ['re_password', 'compare', 'compareAttribute' => 'password', 'type' => 'string'],
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
        $user->email = strtolower($this->email);
        $user->setPassword($this->password);
        $user->status = 9; // 0-borrado.. 9- inac .. 10-act 
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save(false) && $this->sendEmail($user);
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
