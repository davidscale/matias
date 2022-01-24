<?php

namespace backend\models;

use Yii;
use common\models\User as UserCommon;

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
class User extends \yii\db\ActiveRecord
{

    public $password;





    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['username'], 'unique'],
            ['password', 'required'],
            ['password', 'string', 'min' => 8],
        ];
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

    public function signup() {

        if (!$this->validate()) {
            return null;
        }

        if($this->id != null){
            $user = UserCommon::find()->where(['id'=>$this->id])->one();
            if($this->password != null && $this->password != '' ){
            $user->setPassword($this->password);
            }
        }else{
            $user = new UserCommon();
            $user->setPassword($this->password);
        }
        
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
       
        if($this->auth_key == null){
           $user->generateAuthKey();
        }
        if(!$user->save()){
             $this->addErrors($user->getErrors());
             return false;  
        }

       return true;
        
        
    }

}
