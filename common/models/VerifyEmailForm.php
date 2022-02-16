<?php

namespace common\models;

use common\models\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var User
     */
    private $_user;


    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Verifique que el token de correo electrónico no pueda estar en blanco');
        }
        $this->_user = User::findByVerificationToken($token);
        // var_dump($this->_user);die;
        if (!$this->_user) {
            throw new InvalidArgumentException('Token de verificación de correo electrónico incorrecto.');
        }
        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return User|null the saved model or null if saving fails
     */
    public function verifyEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        // var_dump($user);die;
        return $user->save(false) ? $user : null;
    }
}
