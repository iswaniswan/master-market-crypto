<?php

namespace app\models;

use app\components\Helper;
use app\components\Session;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "downline".
 *
 * @property int $id
 * @property int $id_member
 * @property string $id_sponsor
 * @property string $posisi
 * @property int $kiri
 * @property int $kanan
 * @property int $id_upline0
 * @property string $date_created
 * @property string $date_updated
 * @property string $id_group
* @property Member $member

 */


class Downline extends \yii\db\ActiveRecord
{
    const SETS = 2;

    const LEFT = 0;
    const RIGHT = 1;

    public $new_username;
    public $new_password;
    public $new_pin;
    public $new_nama;
    public $new_email;
    public $new_phone;
    public $new_bank;
    public $new_rekening;
    public $new_rekening_an;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'downline';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_member', 'id_sponsor', 'posisi', 'kiri', 'kanan', 'id_upline0', 'id_group'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
            [['id_sponsor', 'new_username', 'new_password', 'new_pin', 'new_nama', 'new_email',
                'new_phone', 'new_bank', 'new_rekening', 'new_rekening_an'
            ], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_member' => 'Id Member',
            'id_sponsor' => 'id Sponsor',
            'posisi' => 'Posisi',
            'id_upline0' => 'Upline 0',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'id_group' => 'ID Group',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'id_member']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user'])->via('member');
    }

    public function getSponsor()
    {
        return $this->hasOne(Member::class, ['id' => 'id_sponsor']);
    }

    public function getUpline0()
    {
        return $this->hasOne(Member::class, ['id' => 'id_upline0']);
    }

    public function getAvailableRegisterUpline($params=[])
    {
        $id_member = @$params['id_member'];

        if ($id_member == null) {
            $id_member = Session::getIdMember();
        }

        $_member = Member::findOne($id_member);
        $_list = [
            $_member->id => ucwords($_member->nama),
        ];

        $query = Downline::find()->where([
            'id_upline0' => $id_member
        ]);

        /** jika belum punya downline */
        if ($query->count() == 0) {
            return $_list;
        }

        if ($query->count() == static::SETS) {
            $_list = [];
        }

        foreach ($query->all() as $downline) {
           $_list += $downline->getAvailableRegisterUpline([
               'id_member' => $downline->id_member
           ]);
        }

        return $_list;
    }

    public function getTextPosisi()
    {
        return $this->posisi == 1 ? 'Right' : 'Left';
    }

//    public static function getMyDownline($params=[])
//    {
//        $id_member = @$params['id_member'];
//
//        $query = Downline::find([
//            'id_upline0' => $id_member
//        ]);
//
//        /** jika SETS kurang dari 2, atau hasil query = 0 maka return member */
//        if ($query->count() < 2) {
//            $_member = Member::findOne($id_member);
//            return [
//                $_member->id => ucwords($_member->nama)
//            ];
//        }
//
//        return [];
//    }

    public function getMyDownline()
    {
        return $this->hasMany(Downline::class, ['id_upline0' => 'id_member']);
    }

    public function getMyDownlineCount()
    {
        $count = 0;
        $query = $this->getMyDownline();
        if ($query->count() > 0) {
            /** @var Downline $downline */
            foreach ($query->all() as $downline) {
                $count += 1;
                $count += $downline->getMyDownlineCount();
            }
        }
        return $count;
    }


    /** @var $member Member */
    /** @var $paket Paket */
    /**
     * @return int
     */
    public function getMyDownlinePointCount()
    {
        $point = 0;
        if (@$this->member->is_active == Member::ACTIVE) {
            $point += @$this->member->paket->reff_bonus_poin;
        }

        $query = $this->getMyDownline();
        if ($query->count() > 0) {
            /** @var Downline $downline */
            foreach ($query->all() as $downline) {
                $point += $downline->getMyDownlinePointCount();
            }
        }
        return $point;
    }

    public function getMyDownlineOmzet()
    {
        $omzet = @$this->member->paket->price;
        if (!$this->member->isActive()) {
            $omzet = 0;
        }

        $query = $this->getMyDownline();
        if ($query->count() > 0) {
            /** @var Downline $downline */
            foreach ($query->all() as $downline) {
                $omzet += $downline->getMyDownlineOmzet();
            }
        }
        return $omzet;
    }

    public function getDataChartBinary($id_group)
    {
        $data = [
            "id" => @$this->member->id,
            "parentId" => $this->id_upline0,
            "idGroup" => $id_group,
            "name" => @$this->member->nama,
            "imageUrl" => $this->member->getLinkPhoto(),
            "posisi" => $this->posisi,
            "is_active" => $this->member->isActive(),
            "downline" => []
        ];

        $allDownline = Downline::findAll([
            'id_upline0' => $this->id_member
        ]);

        foreach ($allDownline as $downline) {
            $data['downline'][] = $downline->getDataChartBinary($id_group);
        }

        return $data;
    }

    public static function getDataChartBinaryAll(Member $member)
    {
        if (@$member->downline != null) {
            $id_group = $member->downline->id_group;
        } else {
            /** akun distributor */
            $id_group = $member->getGroupAsAdmin()->id_group;
        }

        $data = [
            "id" => $member->id,
            "parentId" => 'root',
            "idGroup" => $id_group,
            "name" => $member->nama,
            "imageUrl" => $member->getLinkPhoto(),
            "posisi" => 'root',
            "is_active" => $member->isActive(),
            "downline" => []
        ];
        
        $allDownline = Downline::findAll([
            'id_upline0' => $member->id
        ]);
        
        foreach ($allDownline as $downline) {
            $data['downline'][] = $downline->getDataChartBinary($id_group);
        }        

        return $data;
    }

    public static function getDownlineCount($params=[])
    {
        $count = [
            'total' => 0,
            'left' => 0,
            'right' => 0
        ];

        $id_member = @$params['id_member'];

        $query = Downline::find()->where([
            'id_upline0' => $id_member,
        ]);

        if ($query->count() == 0) {
            return $count;
        }

        /** @var $downline Downline */

        if ($query->count() > 0) {
            $count['total'] += $query->count();

            foreach ($query->all() as $downline) {
                $_query = $downline->getMyDownlineCount();
                if ($downline->posisi == static::LEFT) {
                    $count['left'] += $_query + 1;
                }

                if ($downline->posisi == static::RIGHT) {
                    $count['right'] += $_query + 1;
                }

                $count['total'] += $_query;
            }
        }

        return $count;
    }

    public static function getDownlineOmzetCount($params=[])
    {
        $omzet = [
            'total' => 0,
            'left' => 0,
            'right' => 0
        ];

        $id_member = @$params['id_member'];

        $query = Downline::find()->where([
            'id_upline0' => $id_member,
        ]);

        if ($query->count() == 0) {
            return $omzet;
        }

        /** @var $downline Downline */

        if ($query->count() > 0) {

            foreach ($query->all() as $downline) {

                $_query = $downline->getMyDownlineOmzet();

                if ($downline->posisi == static::LEFT) {
                    $omzet['left'] += $_query;
                }

                if ($downline->posisi == static::RIGHT) {
                    $omzet['right'] += $_query;
                }

                $omzet['total'] += $_query;
            }
        }

        return $omzet;
    }

    public static function getDownlinePoint($params=[])
    {
        $point = [
            'total' => 0,
            'left' => 0,
            'right' => 0
        ];

        $id_member = @$params['id_member'];

        $query = Downline::find()->where([
            'id_upline0' => $id_member,
        ]);

        if ($query->count() == 0) {
            return $point;
        }

        /** @var $downline Downline */

        if ($query->count() > 0) {
            foreach ($query->all() as $downline) {
                $_query = $downline->getMyDownlinePointCount();
                if ($downline->posisi == static::LEFT) {
                    $point['left'] += $_query;
                }

                if ($downline->posisi == static::RIGHT) {
                    $point['right'] += $_query;
                }

                $point['total'] += $_query;
            }
        }

        return $point;
    }

    public static function createFromUser(Member $member, Member $memberSponsor, Member $memberUpline, $position=0)
    {
        $model = new Downline([
            'id_member' => $member->id,
            'id_sponsor' => $memberSponsor->id,
            'posisi' => $position,
            'id_upline0' => $memberUpline->id,
        ]);

        $model->save();
    }

    public static function createFromMember(Member $member, $id_upline0, $posisi=0, $id_group)
    {
        $model = new Downline([
            'id_member' => $member->id,
            'id_sponsor' => $member->id_member_sponsor,
            'posisi' => $posisi,
            'id_upline0' => $id_upline0,
            'id_group' => $id_group
        ]);

        if ($model->save()) {
            return $model;
        }

        var_dump($model->errors);
        die();
    }

    public static function getListPosisi()
    {
        return [
            static::LEFT => 'LEFT',
            static::RIGHT => 'RIGHT'
        ];
    }

    public static function getUpperLevel($id_member, $level=0, $step=0)
    {
        $model = Member::findOne(['id' => $id_member]);

        if ($level == $step) {
            return $model;
        }

        $model = Member::findOne(['id' => $model->id_member_upline]);
        
        if ($model != null) {
            $step += 1;
            return static::getUpperLevel($model->id, $level, $step);
        }

    }

    public static function getAllDownlineID($id_member)
    {
        $member = Member::findOne(['id' => $id_member]);

        $datas = static::getDataChartBinaryAll($member);

        $arrayID = static::extractIdsRecursive($datas);

        return $arrayID;
    }

    public static function extractIdsRecursive($array) {
        $ids = array();
    
        if (isset($array['id'])) {
            $ids[] = $array['id'];
        }
        
        if (isset($array['downline']) && is_array($array['downline'])) {
            foreach ($array['downline'] as $downlineItem) {
                $ids = array_merge($ids, static::extractIdsRecursive($downlineItem));
            }
        }
        
        return $ids;
    }

    public static function findEmptySlot($node, $currentLevel = 0, $side = 'left') {
        if (empty($node['downline']) || count($node['downline']) < 2) {
            return ['id' => $node['id'], 'position' => $side, 'level' => $currentLevel];
        }
    
        $emptySlots = [];
    
        foreach ($node['downline'] as $downline) {
            $emptySlot = static::findEmptySlot($downline, $currentLevel + 1, 'left');
            if ($emptySlot !== null) {
                $emptySlots[] = $emptySlot;
            }
            $emptySlot = static::findEmptySlot($downline, $currentLevel + 1, 'right');
            if ($emptySlot !== null) {
                $emptySlots[] = $emptySlot;
            }
        }
    
        if (empty($emptySlots)) {
            return null;
        }
    
        usort($emptySlots, function ($a, $b) {
            if ($a['level'] === $b['level']) {
                return $a['position'] === 'left' ? -1 : 1;
            }
            return $a['level'] - $b['level'];
        });
    

        return $emptySlots[0];
    }

    public static function findAvailableSlot(Member $member)
    {
        $uplineNodes = Downline::getDataChartBinaryAll($member);
        $emptySlot = Downline::findEmptySlot($uplineNodes);

        $member = Member::findOne(['id' => $emptySlot['id']]);
        $position = Downline::RIGHT;
        if (@$member->downlineRight != null) {
            $position = Downline::LEFT;
        }

        return [
            'id_parent' => $member->id,
            'position' => $position
        ];
    }

}