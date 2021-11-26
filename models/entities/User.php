<?php

namespace app\models\entities;

use yii\db\ActiveRecord;
use app\models\entities\UsuariosPerfiles;
use yii\base\NotSupportedException;
use yii\data\Sort;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord  implements \yii\web\IdentityInterface {

    public  $id;
    public  $username;
    public  $password;
    public  $nombre;
    public  $authKey;
    public  $accessToken;
    public  $avatar;
    public  $vigencia;
    private static $_user;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
     public static function tableName()
    {
        return 'brc_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RUT', 'DV', 'NOMBRE', 'USUARIO', 'CLAVE','AVATAR','VIGENCIA'], 'required'],
            [['RUT'], 'number'],
            [['DV','VIGENCIA'], 'string', 'max' => 1],
            [['NOMBRE','AVATAR'], 'string', 'max' => 255],
            [['USUARIO', 'CLAVE'], 'string', 'max' => 15],
        ];
    }
    
    public function getProfiles()
    {
       return $this->hasMany(UsuariosPerfiles::className(),"");
    }
    
    public static function findIdentity($id) {
        $myId = explode("-", $id);
        $user = static::findOne(['RUT' => $myId[0]]);
        if(!isset($user)){
            return null;
        }else{
            $usuario = new User;
            //self::$id = self::$user->RUT."-".self::$user->DV;
            $usuario->id = $user->RUT."-".$user->DV;
            $usuario->username = $user->USUARIO;
            $usuario->password = $user->CLAVE;
            $usuario->nombre = $user->NOMBRE;
            $usuario->authKey = "KEY".$user->RUT."-".$user->DV;
            $usuario->accessToken = "TOKEN".$user->RUT."-".$user->DV;
            $usuario->avatar = $user->AVATAR;
            $usuario->vigencia = $user->VIGENCIA;
            if (self::$_user === null) {
                self::$_user = new self;
            }
            self::$_user = $usuario;
            return $usuario;
        }
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($usuario) {
        $user = static::findOne(['USUARIO' => $usuario]);
        if(!isset($user)){
            return null;
        }else{
            $usuario = new User;
            //self::$id = self::$user->RUT."-".self::$user->DV;
            $usuario->id = $user->RUT."-".$user->DV;
            $usuario->username = $user->USUARIO;
            $usuario->password = $user->CLAVE;
            $usuario->nombre = $user->NOMBRE;
            $usuario->authKey = "KEY".$user->RUT."-".$user->DV;
            $usuario->accessToken = "TOKEN".$user->RUT."-".$user->DV;
            $usuario->avatar = $user->AVATAR;
            $usuario->vigencia = $user->VIGENCIA;
            if (self::$_user === null) {
                self::$_user = new self;
            }
            self::$_user = $usuario;
            return $usuario;
        }
    }

    public static function getPerfilByUsername($usuario, $id) {
        $query = new \yii\db\Query;
        $query->select([
                    'brc_perfiles.ID_HIJO',
                    'brc_perfiles.DESCRIPCION',
                    'brc_perfiles.IMG',
                    'brc_perfiles.RUTA']
                )
                ->from('brc_usuarios')
                ->join('INNER JOIN', 'brc_usuarios_perfiles', 'brc_usuarios_perfiles.RUT_USUARIO =brc_usuarios.RUT ')
                ->join('INNER JOIN', 'brc_perfiles', 'brc_perfiles.ID_HIJO = brc_usuarios_perfiles.ID_HIJO and brc_perfiles.ID_PADRE = brc_usuarios_perfiles.ID_PADRE')
                ->where(['brc_usuarios.USUARIO' => $usuario])
                ->andWhere(['brc_perfiles.ID_PADRE' => $id])
                ->andWhere(["brc_usuarios_perfiles.VIGENCIA" => "S"]);

        $command = $query->createCommand();
        $data = $command->queryAll();
        return $data;
    }

    public function getId() {
        //var_dump("paso");
        return self::$_user->id;
    }

    public function getAuthKey() {
        return self::$_user->authKey;
    }

    public function validateAuthKey($authKey) {
        return self::$_user->authKey === $authKey;
    }

    public function validatePassword($password) {

        return self::$_user->password === $password;
    }

}
