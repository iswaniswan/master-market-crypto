<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\Helper;
use app\models\Deposit;
use app\models\Downline;
use app\models\FundActive;
use app\models\FundPassive;
use app\models\FundRef;
use app\models\Groups;
use app\models\Member;
use app\models\Paket;
use app\models\RewardClaimed;
use app\models\Roi;
use app\models\User;
use app\models\Wallet;
use DateTime;
use DirectoryIterator;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\FileHelper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionUpdateMemberGroups()
    {
        $count = 0;

        $allMember = Member::find();

        foreach ($allMember->all() as $member) {

            if (@$member->groups != null) {
                continue;
            }

            $group = new Groups([
                'id_group' => Groups::GROUP_ADMIN,
                'id_member' => $member->id,
            ]);
            $group->save();

            $count++;
        }

        echo "done, $count rows updated";
    }

    public function actionInitDatabase()
    {
        Deposit::deleteAll();
        Downline::deleteAll();
        FundActive::deleteAll();
        FundPassive::deleteAll();
        Groups::deleteAll(['<>', 'id_member', 28]);
        Member::deleteAll(['<>', 'id', 28]);
        RewardClaimed::deleteAll();
        User::deleteAll(['<>', 'id', 3378]);

        echo 'done';
    }

    public function actionClearAsset()
    {
        $directory = Yii::getAlias('@app').'\web\assets';
        // echo $directory;

        $dir = new DirectoryIterator($directory);
        foreach ($dir as $folder) {
            if (!$folder->isDot()) {
                $folderName = $folder->getFilename();
                $folderPath = $directory . '\\' . $folderName;
                FileHelper::removeDirectory($folderPath);
            }
        }

    }

    public function actionTestRoi90()
    {
        date_default_timezone_set('Asia/Jakarta');

        $today = new DateTime(); 

        $_message = [];        

        for ($i = 0; $i < 90; $i++) {
            /** ROI */
            $currentDay = $today->format('Y-m-d');

            /** get all active distributor */
            $allDistributor = Member::find()->where([
                'id_paket' => Paket::DISTRIBUTOR,
                'is_active' => Member::ACTIVE
            ])->all();

            /** get rate ROI */
            $lastRoi = Roi::find()->orderBy(['date_created' => SORT_DESC])->one();
            $rate = $lastRoi->roi;

            $paket = Paket::findOne(['id' => Paket::DISTRIBUTOR]);

            $roiValue = $rate * $paket->price /100;

            foreach ($allDistributor as $member) {
                if ($member->isAdmin()) {
                    continue;
                }
    
                $fundRoi = FundPassive::find()->where([
                    'id_member' => $member->id,
                    'id_fund_ref' => FundRef::ROI
                ])->andFilterWhere([
                    'like', 'date_created', $currentDay
                ]);
    
                if ($fundRoi->one() != null) {
                    continue;
                }
    
                $fundPassive = new FundPassive([
                    'id_member' => $member->id,
                    'id_fund_ref' => FundRef::ROI,
                    'credit' => $roiValue,
                    'id_trx' => Helper::generateNomorTransaksi(),
                    'date_created' => $today->format('Y-m-d H:i:s')
                ]);
    
                if ($fundPassive->save()) {    
                    $_message[] = "$currentDay - $member->nama get $roiValue";
                } else {
                    $_message[] = $fundPassive->errors;
                }
            }
            
            $today->modify('-1 day');
        }

        var_dump($_message);
    }

    public function actionCreateWalletUsdt()
    {
        $allMember = Member::find()->where([
            'is_active' => 1
        ])->all();

        $id_crypto = Wallet::USDT;

        foreach ($allMember as $member) {

            $wallet = Wallet::findOrCreate($member->id, $id_crypto);
            $wallet->updateAttributes([
                'balance' => 50,
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        }

        echo 'success';

    }

}
