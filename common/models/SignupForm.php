<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 * @property string $password_hash
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
            ['username', 'required', 'message' => 'El DNI no puede estar vacio.'],
            ['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'Este nombre de usuario ya ha sido tomado.'],
            ['username', 'string', 'min' => 8, 'max' => 8, 'message' => 'El DNI tiene solo 8 dígitos.'],
            ['username', 'match', 'pattern' => '/^[0-9]{8}$/', 'message' => 'El DNI debe ser numérico'], 

            ['email', 'trim'],
            ['email', 'required', 'message' => 'El email no puede estar vacio.'],
            ['email', 'email'],
            ['email', 'match', 'pattern' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'],
            ['email', 'string', 'max' => 50],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'Esta dirección de correo electrónico ya se encuntra cargada.'],

            ['password', 'required', 'message' => 'La contraseñas no puede estar vacio.'],
            ['password', 'match', 
                    'pattern' => '/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', 
                    'message' => 'La contraseña debe contener un carácter alfabético, una mayúscula y un numero.'],

            ['re_password', 'required', 'message' => 'La confirmacion de contraseñas no puede estar vacio.'],
            ['re_password', 'compare', 
                    'compareAttribute' => 'password', 
                    'type' => 'string',
                    'message' => 'Las contraseñas no son iguales.'
                ],
            
            [['status'], 'required'],            
            [['status', 'created_at', 'updated_at'], 'trim'],
            [['status', 'created_at', 'updated_at'], 'integer'],
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
        $user->status = 10; // 0-borrado.. 9- inac .. 10-act 
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

