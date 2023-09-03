<?php

namespace app\models;

use Exception;
use Yii;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_paket
 * @property string|null $nama
 * @property string|null $no_ktp
 * @property string|null $phone
 * @property string|null $alamat
 * @property int|null $id_reff_kotakab
 * @property int|null $id_reff_provinsi
 * @property string|null $kotakab
 * @property string|null $provinsi
 * @property string|null $kodepos
 * @property string|null $info
 * @property string|null $bank
 * @property string|null $rekening
 * @property string|null $rekening_an
 * @property string|null $referral_code
 * @property int|null $id_member_sponsor
 * @property int|null $id_member_upline
 * @property string|null $photo
 * @property int|null $is_verified
 * @property int $is_active
 * @property string|null $date_active
 * @property string|null $date_created
 * @property int $is_deleted
 * @property Paket $paket
 * @property User $user
 * @property Groups $groups
 * @property Downline $downline
 * @property Kotakab $kotakabs
 * @property Member $downlineRight
 * @property Member $downlineLeft
 */
class Member extends \yii\db\ActiveRecord
{

    const ACTIVE = 1;

    public $nilai_omzet;

    public $email;
    public $username;
    public $password;
    public $pin;

    public $posisi;

    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    public static function getListMember($keyword)
    {
        $query = static::find();

        $query->andFilterWhere(['like', 'member.nama', $keyword]);

        return $query->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'integer'],
            [['id_paket', 'id_reff_kotakab', 'id_reff_provinsi', 'id_member_sponsor', 'id_member_upline', 'is_verified', 'is_active', 
                'is_deleted', 'id_member_approved_by'], 'safe'],
            [['info'], 'string'],
            [['date_active', 'date_created'], 'safe'],
            [['nama', 'alamat', 'kotakab', 'provinsi', 'referral_code', 'photo'], 'string', 'max' => 255],
            [['no_ktp'], 'string', 'max' => 16],
            [['phone'], 'string', 'max' => 15],
            [['kodepos', 'rekening', 'rekening_an'], 'string', 'max' => 100],
            [['bank'], 'string', 'max' => 50],
            [['id_user'], 'unique'],
            [['email', 'username', 'password', 'pin', 'posisi'], 'safe'],
            // [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'User',
            'id_paket' => 'Paket',
            'nama' => 'Nama',
            'no_ktp' => 'No Ktp',
            'phone' => 'Phone',
            'alamat' => 'Alamat',
            'id_reff_kotakab' => 'Kotakab',
            'id_reff_provinsi' => 'Provinsi',
            'kotakab' => 'Kotakab',
            'provinsi' => 'Provinsi',
            'kodepos' => 'Kodepos',
            'info' => 'Info',
            'bank' => 'Bank',
            'rekening' => 'Rekening',
            'rekening_an' => 'Rekening An',
            'referral_code' => 'Refferal Code',
            'id_member_sponsor' => 'Id Member Sponsor',
            'id_member_upline' => 'Id Member Upline',
            'photo' => 'Photo',
            'is_verified' => 'Is Verified',
            'is_active' => 'Is Active',
            'id_member_approved_by' => 'Id Member Approved By',
            'date_active' => 'Date Active',
            'date_created' => 'Date Created',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public function getPaket()
    {
        return $this->hasOne(Paket::class, ['id' => 'id_paket']);
    }

    public static function createActiveMember($id_paket, $id_user, $id_member_upline=null)
    {
        $model = User::findOne(['id' => $id_user]);
        if ($model == null) {            
            throw new Exception('error ID User not found', 1);
        }

        /** create referral code */        
        $randomString = static::generateUniqueReferralCode();

        $member = new Member([
            'id_user' => $id_user,
            'id_paket' => $id_paket,
            'id_member_upline' => $id_member_upline,
            'referral_code' => $randomString,
            'date_active' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ]);

        if (!$member->save()) {
            var_dump($member->errors);
            return false;
        }

        return true;
    }

    public static function generateUniqueReferralCode()
    {
        $randomString = strtoupper(
            Yii::$app->security->generateRandomString(6)
        );
        $randomString = str_replace('_', rand(0, 9), $randomString);
        $randomString = str_replace('-', rand(0, 9), $randomString);
        while (static::isCodeReferralExists($randomString)) {
            $randomString = static::generateUniqueReferralCode();
        }

        return $randomString;
    }

    public static function isCodeReferralExists($code)
    {
        return static::findOne([
            'referral_code' => $code
        ]) != null;
    }


    /**@return Member|null */
    public static function createFromUser(User $user, Member $memberSponsor, Member $memberUpline)
    {
        $model = new Member([
            'id_user' => $user->id,
            'id_member_sponsor' => $memberSponsor->id,
            'id_member_upline' => $memberUpline->id,
        ]);

        $model->save();

        return $model;
    }

    public static function getDownlineCount($params=[])
    {
        return 0;
    }

    public static function getDownlineOmzetCount($params=[])
    {
        return 0;
    }

    /**
     * 
     */
    public static function FindAvailableMemberUpline($id_member=null)
    {
        $downline = new Downline();
        $upline = $downline->getAvailableRegisterUpline([
            'id_member' => $id_member
        ]);        

        $upline = array_reverse($upline, true);

        $model = Member::findOne(['id' => array_keys($upline)[0]]);

        return $model;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function getLinkPhoto()
    {
        $defaultPhoto = Yii::getAlias('@web') . '/images/no-photo.jpg';
        if ($this->photo == null) {
            return $defaultPhoto;
        }

        return Yii::getAlias('@web') . '/uploads/'.$this->photo;
    }

    public function isVerified()
    {
        return $this->is_verified == 1;
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }

    public function getTotalNilaiOmzet()
    {
        $count_omzet = Downline::getDownlineOmzetCount(['id_member' => $this->id]);
        $omzetLeft = $count_omzet['left'];
        $omzetRight = $count_omzet['right'];

        return $omzetLeft + $omzetRight;
    }

    public static function getAllMemberCount($params=[])
    {
        $id_paket = @$params['id_paket'];

        if ($id_paket == null) {
            return 0;
        }

        $query = static::find()->where([
            'id_paket' => $id_paket
        ]);

        if (@$params['id_group'] != null) {
            $query->innerJoin('groups', 
                'groups.id_member = member.id' 
            )->andWhere(['id_group' => $params['id_group']]);
        }

        if (@$params['id_member_sponsor'] != null) {
            $query->andWhere(['id_member_sponsor' => $params['id_member_sponsor']]);
        }

        // $command = $query->createCommand()->getRawSql();
        // var_dump($command); die();
        
        return $query->count();
    }

    public function getRoleByPaket()
    {
        if ($this->id_paket == Paket::DISTRIBUTOR) {
            return Role::DISTRIBUTOR;
        }

        if ($this->id_paket == Paket::AGEN) {
            return Role::AGEN;
        }

        if ($this->id_paket == Paket::PENGECER) {
            return Role::PENGECER;
        }

        return Role::MEMBER;
    }

    public function getGroups()
    {
        return $this->hasOne(Groups::class, ['id_member' => 'id'])->andOnCondition(['is_admin_group' => 0]);
    }

    public function getGroupAsAdmin()
    {
        $query = Groups::findOne([
            'id_member' => $this->id, 'is_admin_group' => Groups::GROUP_ADMIN
        ]);

        return $query;
    }

    public function getDownline()
    {
        return $this->hasOne(Downline::class, ['id_member' => 'id']);
    }

    public function getKotakabs()
    {
        return $this->hasOne(Kotakab::class, ['id' => 'id_reff_kotakab']);
    }

    public static function groupIdByReferralCode($referral_code)
    {
        $member = static::findOne([
            'referral_code' => $referral_code
        ]);

        return $member->groups->id_group ?? Groups::ADMIN;
    }

    public function getDownlineRight()
    {
        return Downline::findOne([
            'id_upline0' => $this->id,
            'posisi' => Downline::RIGHT
        ]);
    }

    public function getDownlineLeft()
    {
        return Downline::findOne([
            'id_upline0' => $this->id,
            'posisi' => Downline::LEFT
        ]);
    }

    public function isDistributor()
    {
        return @$this->user->id_role == Role::DISTRIBUTOR;
    }

    public function isAdmin()
    {
        return @$this->user->id_role == Role::ADMIN;
    }

    public static function getListMemberGroup($id_group, $keyword='')
    {
        $query = static::find();

        $query->innerJoin('groups', 'groups.id_member = member.id AND groups.id_group = ' . $id_group);

        $query->andFilterWhere(['like', 'member.nama', $keyword]);

        // $command = $query->createCommand()->getRawSql();
        // var_dump($command); die();

        return $query->all();
    }

    public function isDoneCashback()
    {
        $fundPassive = FundPassive::find()->where([
            'id_member' => $this->id,
            'id_fund_ref' => FundRef::CASHBACK,            
        ])->one();

        if ($fundPassive != null) {
            return true;
        }

        return false;
    }

    public function upload()
    {
        $this->imageFile = UploadedFile::getInstanceByName($this->photo);
        if ($this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension)) {             
            return true;
        } else {
            return false;
        }
    }

    public function needToReinvest()
    {
        if ($this->isDistributor() == true) {
            $countRoi = FundPassive::getCount($this->id, FundRef::ROI);

            if ($countRoi >= 90) {
                return true;
            }
        }

        return false;
    }

}
