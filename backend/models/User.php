<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 */
class User extends ActiveRecord implements IdentityInterface
{

    public $re_password;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //usernar guarda el DNI en formato argentino
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

            ['password_hash', 'required', 'message' => 'La contraseñas no puede estar vacio.'],
            ['password_hash', 'match', 
                    'pattern' => '/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', 
                    'message' => 'La contraseña debe contener un carácter alfabético, una mayúscula y un numero.'],

            ['re_password', 'required', 'message' => 'La confirmacion de contraseñas no puede estar vacio.'],
            ['re_password', 'compare', 
                    'compareAttribute' => 'password_hash', 
                    'type' => 'string',
                    'message' => 'Las contraseñas no son iguales.'
                ],
            
            [['status'], 'required'],            
            [['status', 'created_at', 'updated_at'], 'trim'],
            [['status', 'created_at', 'updated_at'], 'integer'],

        ];
    }

    public function create()
    {
        $date = date_create();
        
        // if (!$this->validate()) {
        //     return null;
        // }

        $this->email = strtolower($this->email);
        // $this->setPassword($this->password_hash);
        $this->setPassword('Dev2021');
        $this->generateAuthKey();
        $this->generateEmailVerificationToken();
        $this->generatePasswordResetToken();
        $this->created_at = $this->updated_at = date_timestamp_get($date);

        return $this->save(false) && $this->sendEmail($this);
    }

    // public function update()
    // {
    //     $date = date_create();
        
    //     $this->email = strtolower($this->email);
    //     // $this->setPassword($this->password_hash);
    //     $this->setPassword('Dev2021');
    //     $this->generateAuthKey();
    //     $this->generateEmailVerificationToken();
    //     $this->generatePasswordResetToken();
    //     $this->created_at = $this->updated_at = date_timestamp_get($date);

    //     return $this->save(false) && $this->sendEmail($this);
    // }

    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Registro de cuenta en ' . Yii::$app->name)
            ->send();
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'verification_token' => Yii::t('app', 'Verification Token'),
        ];
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    

    //viejo
    // public function signup() {

    //     if (!$this->validate()) {
    //         return null;
    //     }

    //     if($this->id != null){
    //         $user = UserCommon::find()->where(['id'=>$this->id])->one();
    //         if($this->password != null && $this->password != '' ){
    //         $user->setPassword($this->password);
    //         }
    //     }else{
    //         $user = new UserCommon();
    //         $user->setPassword($this->password);
    //     }
        
    //     $user->username = $this->username;
    //     $user->email = $this->email;
    //     $user->status = $this->status;
       
    //     if($this->auth_key == null){
    //        $user->generateAuthKey();
    //     }
    //     if(!$user->save()){
    //          $this->addErrors($user->getErrors());
    //          return false;  
    //     }

    //    return true;
        
        
    // }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @param boolean $active
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @param boolean $active
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!self::isPasswordResetTokenValid($token)) {
            return null;
        }

        return self::findOne([//busco pass no me interesa el toklen
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        // var_dump($token);die;
        return self::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }
        // var_dump($token);die();

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $d = ($timestamp + $expire) >= time();

        // var_dump($d);die();

        return $d;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }





}
