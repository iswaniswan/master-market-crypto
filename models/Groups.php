<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property int|null $id_group
 * @property int|null $id_member
 * @property int|null $is_admin_group
 * @property int $is_active
 * @property Member $memberAdmin
 * @property Member $member
 */
class Groups extends \yii\db\ActiveRecord
{

    const GROUP_ADMIN = 1;
    const ADMIN = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_group', 'id_member', 'is_admin_group', 'is_active'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_group' => 'Id Group',
            'id_member' => 'Id Member',
            'is_admin_group' => 'Is Admin Group',
            'is_active' => 'Is Active',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getMemberAdmin()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member', 'is_admin_group' => static::GROUP_ADMIN]);
    }

    public static function getMemberAdminGroup($id_group)
    {
        $group = static::findOne([
            'id_group' => $id_group,
            'is_admin_group' => static::GROUP_ADMIN
        ]);

        return $group->member;
    }

    public static function findMemberGroup(Member $member)
    {
        return static::findOne([
            'id_member' => $member->id,
            'is_admin_group' => 0
        ]);
    }

    public static function findAdminGroup($id_group)
    {
        return static::findOne([
            'id_group' => $id_group,
            'is_admin_group' => static::ADMIN
        ]);
    }

}
