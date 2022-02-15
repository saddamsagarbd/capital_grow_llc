<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDetails extends Model
{
    use HasFactory;

    public function getPendingUsersList()
    {
        return DB::table("payment_confirm_requests as pcr")
                ->join("users as u", "u.id", "=", "pcr.user_id")
                ->join('userprofiles as up', 'u.user_id', '=', 'up.user_id')
                ->select('up.first_name', 'up.last_name', 'pcr.*', 'u.username')
                ->where("u.user_status", 3)
                ->where("u.user_role", 2)
                ->get();
    }

    public function getActiveUsersList()
    {
        return DB::table("payment_confirm_requests as pcr")
                ->join("users as u", "u.id", "=", "pcr.user_id")
                ->join('userprofiles as up', 'u.user_id', '=', 'up.user_id')
                ->select('up.first_name', 'up.last_name', 'pcr.*', 'u.username')
                ->where("u.user_status", 1)
                ->where("u.user_role", 2)
                ->get();
    }

    public function getUserByIdN($id)
    {
        return DB::table('users')
                        ->join('userprofiles', 'users.user_id', '=', 'userprofiles.user_id')
                        ->select('users.*', 'userprofiles.*')
                        ->where('users.id', $id)
                        ->first();
    }

    public function getUserPlacementIdByIdN($id)
    {
        return DB::table('users')
                        ->join('userprofiles', 'users.user_id', '=', 'userprofiles.user_id')
                        ->select('userprofiles.placement_id')
                        ->where('users.id', $id)
                        ->first();
    }

    public function getUserAccountDetailsByUserid($user_id)
    {
        return DB::table('user_accounts AS uac')
                ->join('users', 'users.user_id', '=', 'uac.user_id')
                ->select('uac.account_number', 'uac.account_title')
                ->where('uac.user_id', $user_id)
                ->orWhere('users.id', $user_id)
                ->where('uac.account_status', 1)
                ->first();
    }

    public function getUserByUsername($username)
    {
        return DB::table('users')
                ->join('userprofiles', 'users.user_id', '=', 'userprofiles.user_id')
                ->select('users.id', 'users.user_id', 'users.username', 'userprofiles.first_name', 'userprofiles.last_name', 'userprofiles.profile_img')
                ->where('users.username', $username)
                ->orWhere('users.email', $username)
                ->first();
    }

    public function upStatus($id)
    {
        $upStatus = DB::table('users')
                ->where('id', (int) $data["user_id"])
                ->update(['user_status' => (int) 1]);

    }

    public function updateUserDetails($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        $upStatus = DB::table('users')
                ->where('id', (int) $id)
                ->update(['user_status' => (int) 1]);

        $upRegBalance = DB::table('userprofiles')
                        ->where('user_id', $user->user_id)
                        ->update([
                            'registration_balance' => number_format("185", 2, '.', ''),
                            'reference_enable' => 1,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
        return true;
    }

    public function getAccountDetailsByUserId($user_id)
    {
        return DB::table('user_accounts')
                        ->select('user_accounts.account_number', 'user_accounts.account_title')
                        ->where('user_accounts.user_id', $user_id)
                        ->first();
    }

    public function getAccountBalanceById($id)
    {
        return DB::table('income_statements')
                ->selectRaw('sum(credit) AS totalCredit, sum(debit) AS totalDebit')
                ->where('user_id', $id)
                ->groupBy('user_id')
                ->first();
    }

    public function lastWeeklyBonusSharedIn($id)
    {
        return DB::table('userprofiles')
                ->join('users', 'users.user_id', '=', 'userprofiles.user_id')
                ->selectRaw('userprofiles.last_weekly_bonus_earned')
                ->where('users.id', $id)
                ->first();
    }

    public function useronGboard($id)
    {
        return DB::table('golden_board')
                ->selectRaw('created_at')
                ->where('user_id', $id)
                ->first();
    }

    public function getTotalreferalUsersByPlacementId($plc_id)
    {
        // return DB::table('userprofiles')
        //         ->selectRaw('count(id) AS total')
        //         ->where('placement_id', $plc_id)
        //         ->first();
        return DB::table('refered_users_count')
                ->selectRaw('total_referal')
                ->where('user_id', $plc_id)
                ->first();

    }

    public function isExistOnGoldenBoard($id, $table)
    {
        return DB::table($table)
                ->where('id', $id)
                ->first();
    }

    public function switchOnGoldenBoard($id, $table)
    {
        $totalRows = DB::table($table)
                ->selectRaw('count(id) AS total')
                ->first();
        $data = [
            'user_id' => $id,
            'queue_count' => (isset($totalRows->total) && $totalRows->total > 0)?($totalRows->total * 30 + 30):30,
            'created_at' => date("Y-m-d H:i:s")
        ];
        $isert = DB::table($table)
                ->insert($data);

        if($isert)
        {
            return DB::table('users')
                ->where('id', $id)
                ->update(['user_on_board' => (int) 1]);
        }
        return true;
    }

    public function updateWeeklyBonusDate($id)
    {
        DB::table('userprofiles')
            ->where('id', $id)
            ->update(['last_weekly_bonus_earned' => date('Y-m-d H:i:s')]);
    }

    public function switchOnDiamondBoard($table)
    {
        $guDtl = DB::table('golden_board')->where('is_switched_to_diamond_board', 0)->first();
        $getTotalRows = DB::table('golden_board')
                        ->where('id', '>' , $guDtl->id)
                        ->where('is_switched_to_diamond_board', 0)
                        ->get();
    
        $guDtl->queue_count = 10;
        
        if(!is_null($getTotalRows) && (count($getTotalRows) >= (int) $guDtl->queue_count))
        
        {
            $dBoard = [
                'user_id' => $guDtl->user_id,
                'created_at' => date("Y-m-d H:i:s")
            ];

            $isert = DB::table($table)
                ->insert($dBoard);
            if($isert)
            {
                DB::table('golden_board')
                        ->where('id', $guDtl->id)
                        ->update([
                            'is_bonus_shared' => (int) 1,
                            'is_switched_to_diamond_board' => (int) 1
                        ]);
                return ['status' => true, 'top_id' => $guDtl->user_id];
            }
        }

        return false;
    }

    public function getReferedUsersList($id)
    {
        return DB::table("users as u")
                        ->join('userprofiles as up', 'u.user_id', '=', 'up.user_id')
                        ->select('up.*', 'u.id as uid', 'u.email', 'u.username')
                        ->where("u.user_status", 1)
                        ->where("u.user_role", 2)
                        ->where("up.placement_id", $id)
                        ->get();

    }

    public function getIncomeStatement($id)
    {
        return DB::table("income_statements as icst")
                        ->select('icst.*')
                        ->where("icst.user_id", $id)
                        ->get();
    }

    public function getTotalRefarals($user_id)
    {
        return DB::table("refered_users_count as ruc")
                ->select('ruc.*')
                ->where("ruc.user_id", $user_id)
                ->first();
    }

    

    public function getGoldenBoardDtlByUserId($user_id)
    {
        return DB::table("golden_board as gb")
                ->select('gb.*')
                ->where("gb.user_id", $user_id)
                ->first();
    }

    public function getTofiveGoldenBoardMembers()
    {
        return DB::table("golden_board as gb")
                ->join('users', 'users.id', '=', 'gb.user_id')
                ->join('userprofiles as up', 'up.user_id', '=', 'users.user_id')
                ->select('gb.*', 'up.first_name', 'up.last_name', 'up.profile_img')
                ->where("gb.is_switched_to_diamond_board", 0)
                ->orderBy('gb.id', 'asc')
                ->take(5)
                ->get();
    }

    public function getTofiveDiamondBoardMembers()
    {
        return DB::table("diamond_board as dmndb")
                ->join('users', 'users.id', '=', 'dmndb.user_id')
                ->join('userprofiles as up', 'up.user_id', '=', 'users.user_id')
                ->select('dmndb.*', 'up.first_name', 'up.last_name', 'up.profile_img')
                ->orderBy('dmndb.id', 'asc')
                ->take(5)
                ->get();
    }

    public function saveAccountInfo($data, $table)
    {
        return DB::table($table)
                ->insert($data);
    }

    public function getUserAccountsInfo($id)
    {
        return DB::table("user_account_information")
            ->where('user_id', $id)
            ->get();
    }

    public function updateOtp($id, $otp)
    {
        return DB::table('users')
                ->where('id', (int) $id)
                ->update(['otp' => (int) $otp, 'otp_sent_at' => date('Y-m-d H:i:s')]);

    }

    public function getOtpByUserID($id)
    {
        return DB::table('users')
                ->where('id', (int) $id)
                ->select('otp')
                ->first();
    }

    public function sendWithdrawalRequestForAuthorization($data)
    {
        $param = [
            'user_id' => $data['user_id'],
            'withdrawal_amount' => $data['total_amount'],
            'withdrawal_details' => $data['withdrawal_details'],
            'created_by' => $data['user_id'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        return DB::table('balance_withdrawal_requests')
            ->insert($param);
    }

    public function getWithdrawalRequestByID($id)
    {
        return DB::table('balance_withdrawal_requests')
                ->where('user_id', (int) $id)
                // ->where('request_status', 2)
                ->get();
    }

    public function getAllWithdrawalRequests()
    {
        return DB::table('balance_withdrawal_requests as bwr')
                ->join("users as u", "u.id", "=", "bwr.user_id")
                ->join('userprofiles as up', 'u.user_id', '=', 'up.user_id')
                ->select('bwr.*', 'up.first_name', 'up.last_name', 'up.contact_number', 'up.profile_img')
                ->get();
    }

    public function updateWithdrawalRequest($data)
    {
        return DB::table('balance_withdrawal_requests')
                    ->where('user_id', $data["user_id"])
                    ->update(['request_status' => (int) $data["req_val"]]);
    }

    public function getPlacementReferenceUsernameByIdN($id)
    {
        return DB::table('users')
                // ->join('userprofiles', 'users.user_id', '=', 'userprofiles.user_id')
                ->select('username')
                ->where('id', (int) $id)
                ->first();
    }
}
