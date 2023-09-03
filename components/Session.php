<?php

namespace app\components;

use app\models\Groups;
use app\models\Role;
use Codeception\Step\Retry;
use Yii;

class Session extends \yii\web\Session
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public static function isAdmin()
    {        
        return (int) Yii::$app->user->identity->id_role == Role::ADMIN;
    }

    public static function isDistributor()
    {        
        return (int) Yii::$app->user->identity->id_role == Role::DISTRIBUTOR;
    }

    public function getPlatform()
    {
        return $this->get('platform', 'web');
    }

    public function isMobile()
    {
        if($this->getPlatform() == 'mobile') {
            return true;
        }

        return false;
    }

    public static function getUsername()
    {
        return Yii::$app->user->identity->username;
    }

    public static function getIdUser()
    {
        return (int) @Yii::$app->user->identity->id;
    }

    public static function getIdMember()
    {
        $idUser = @Yii::$app->user->identity->id;

        $query = \app\models\Member::findOne(['id_user' => $idUser]);

        return $query->id ?? null;
    }

    public static function isMemberActive()
    {
        $idUser = @Yii::$app->user->identity->id;

        $query = \app\models\Member::findOne(['id_user' => $idUser]);

        return $query->is_active == 1;
    }

    public static function getIdGroups()
    {
        $idUser = @Yii::$app->user->identity->id;

        $member = \app\models\Member::findOne(['id_user' => $idUser]);

        $groups = Groups::findOne([
            'id_member' => $member->id,
            'is_admin_group' => 0
        ]);

        if ($groups != null) {
            return $groups->id_group;
        }

        return Groups::GROUP_ADMIN;
    }

    public static function getIdGroupAsAdminGroup()
    {
        $idUser = @Yii::$app->user->identity->id;

        $member = \app\models\Member::findOne(['id_user' => $idUser]);

        $groups = Groups::findOne([
            'id_member' => $member->id,
            'is_admin_group' => 1
        ]);

        if ($groups != null) {
            return $groups->id_group;
        }

        return Groups::GROUP_ADMIN;
    }

}