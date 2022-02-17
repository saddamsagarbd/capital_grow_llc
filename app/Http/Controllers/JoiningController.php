<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\ShareBonus;
use App\Models\IncomeStatement;
use App\Models\ReferenceCount;
use App\Mail\SendOTPMail;

use App\Events\CreateUserProfile;
use App\Events\CreateAccount;
use App\Events\TransactionEvent;

class JoiningController extends Controller
{

    public function index()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        return view('admin_panel.joining');
    }

    public function getRefDetails(Request $req)
    {
        $username = $req["username"];

        $userDetails = new UserDetails();
        $getUserDetails = $userDetails->getUserByUsername($username);
        echo json_encode($getUserDetails);
    }

    public function file_upload($image)
    {
        $filename = 'profile_' . time() . '.' . $image[0]->getClientOriginalExtension();
        
        $upload_path = base_path('public');

        $path = $image[0]->move(

            $upload_path.'/uploads/profile_images/', $filename

        );
        
        return $filename;
    }

    public function makeUserJoining(Request $req)
    {
        try {
            $rules = [
                'refer_username'    => 'required',
                'placement_username'    => 'required',
                'first_name'        => 'required|string|max:255',
                'last_name'         => 'required|string|max:255',
                'email'             => 'required|email|unique:users',
                'username'          => 'required',
                'contact_number'    => 'required',
                'password'          => 'required|string|min:8|confirmed',
                'gender'            => 'required',
                'identification_no' => 'required',
                'dob'               => 'required',
                'img'               => 'required'
            ];
        
            $customMessages = [
                'refer_username.required'       => 'You must enter refered user',
                'placement_username.required'   => 'You must enter placement username',
                'first_name.required'           => 'You must enter first name',
                'last_name.required'            => 'You must enter last name',
                'email.required'                => 'You must enter email',
                'username.required'             => 'You must enter username',
                'contact_number.required'       => 'You must enter contact number',
                'password.required'             => 'You must enter password',
                'gender.required'               => 'You must select your gender',
                'identification_no.required'    => 'You must enter your identification number',
                'dob.required'                  => 'You must enter your date of birth',
                'img.required'                  => 'You must choose a file',
            ];
        
            $this->validate($req, $rules, $customMessages);
            $filename = "";

            if ($req->hasfile('img')) {
                $filename = $this->file_upload($req->file('img'));
            }

            $data = $req->except(["_token", "img"]);

            $ref_username = $data['refer_username']; 
            $plc_username = $data['placement_username']; 

            $user_profile = new UserDetails();

            $getReferenceUserid = $user_profile->getUserByUsername($data['refer_username']);
            $getPlacementUserid = $user_profile->getUserByUsername($data['placement_username']);

            if(isset($getReferenceUserid->id)){
                $data['refer_username'] = $getReferenceUserid->id;
            }

            if(isset($getPlacementUserid->id)){
                $data['placement_username'] = $getPlacementUserid->id;
            }

            $data['user_id'] = mt_rand(100000,999999).strtotime(now());
            $data['filename'] = $filename;
    
            $userData = [
                'user_id'       => $data['user_id'],
                'username'      => $data['username'],
                'email'         => $data['email'],
                'password'      => Hash::make($data['password'])
            ];
    
            $user = User::create($userData);

            if(isset($user->id))
            {
                event(new CreateUserProfile((object) $data));
        
                event(new CreateAccount((object) $data));

                return redirect('/joining')->with('msg', 'User joined successfully');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function weeklyBonusShared()
    {
        $sharedbonus = new ShareBonus();
        $user_profile = new UserDetails();

        $ids = [];

        $getWeeklybonusElegibleUsersFromGB = $sharedbonus->getWeeklybonusElegibleUsersFromGB();
        $getWeeklybonusElegibleUsersFromDB = $sharedbonus->getWeeklybonusElegibleUsersFromDB();

        foreach($getWeeklybonusElegibleUsersFromGB as $key => $gb_uid)
        {
            array_push($ids, $gb_uid->gb_uid);
        }

        foreach($getWeeklybonusElegibleUsersFromDB as $key => $db_uid)
        {
            array_push($ids, $db_uid->user_id);
        }

        arsort($ids);

        $wk_amt = 3;

        foreach($ids as $key => $uid)
        {            
            $lastWeeklyBonusSharedIn = $user_profile->lastWeeklyBonusSharedIn($uid);

            $lstWklErndData = "";

            if(isset($lastWeeklyBonusSharedIn->last_weekly_bonus_earned))
            {
                $lstWklErndData = $lastWeeklyBonusSharedIn->last_weekly_bonus_earned;
    
            }else{
                $useronGboard = $user_profile->useronGboard($uid);
                $lstWklErndData = $useronGboard->created_at;
            }
            $now = date('Y-m-d H:i:s'); // or your date as well
            $your_date = strtotime($lstWklErndData);
            $datediff = strtotime($now) - $your_date;

            if(round($datediff / (60 * 60 * 24)) >= 7)
            {
                $getAccountNumber = $user_profile->getUserAccountDetailsByUserid($uid);
                $getAccountBalance = $user_profile->getAccountBalanceById($uid);
                
                if(is_null($getAccountBalance)){
                    $totalCredit = 0;
                }else{
                    $totalCredit = (isset($getAccountBalance->totalCredit) && $getAccountBalance->totalCredit == null)?0:$getAccountBalance->totalCredit;
                }
        
                if(is_null($getAccountBalance)){
                    $totalDebit = 0;
                }else{
                    $totalDebit = (isset($getAccountBalance->totalDebit) && $getAccountBalance->totalDebit == null)?0:$getAccountBalance->totalDebit;
                }
    
                $balance = ((number_format((float)$totalCredit, 2, '.', '') - (number_format((float)$totalDebit, 2, '.', ''))) + number_format((float)$wk_amt, 2, '.', ''));
                
                $txn_data = [
                    'for' => 'weekly_bonus',
                    'ref_id' => $uid,
                    'account_number' => $getAccountNumber->account_number,
                    'trxn_id' => mt_rand(10000000, 99999999),
                    'credit' => number_format($wk_amt, 2, '.', ''),
                    'balance' => number_format($balance, 2, '.', ''),
                    'ref_from' => 'Weekly bonus shared'
                ];
        
                event(new TransactionEvent($txn_data));

                $user_profile->updateWeeklyBonusDate($uid);

            }           
        }

        return true;
    }

    public function test()
    {
        $ref_username = "user1";
        $user_id = "2418631643458208";
        $user_id = 4;

        // Referal User count
        
        // $this->increaseReferalUsers($ref_username);

        // Share reference bonus

        // $this->shareReferenceBonus($ref_username, $user_id);

        // Share generation bonus

        // $this->generationIncentive($user_id);

        // promote user level

        // $this->promoteUserToGoldenBoard($ref_username);
    }

    public function balanceWithdrawalForm()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $userDtl = new UserDetails();
        $id = Auth::user()->id;
        $totalDbCr = $userDtl->getAccountBalanceById($id);
        $existingWithdrawalRequest = $userDtl->getWithdrawalRequestByID($id);
        $existingWithdrawalRequest = (count($existingWithdrawalRequest) > 0)?true:false;


        $totalDebit = (isset($totalDbCr->totalDebit) && $totalDbCr->totalDebit != null)?$totalDbCr->totalDebit:0;
        $totalCredit = (isset($totalDbCr->totalCredit) && $totalDbCr->totalCredit != null)?$totalDbCr->totalCredit:0;

        $balance = number_format(number_format((float)$totalCredit, 2, '.', '') - number_format((float)$totalDebit, 2, '.', ''), 2, '.', '');

        return view('admin_panel.balance_withdrawal', ['balance' => $balance, 'existingWithdrawalRequest' => $existingWithdrawalRequest]);
    }

    // Function to generate OTP 
    public function generateNumericOTP($n)
    {
        $generator = "1357902468"; 
        
        $result = "";
        
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        
        // Returning the result 
        return $result;
    }

    public function sendOtpEmail($otpCode, $data)
    {
        // send company account details via Mail
        $details = [
            'otp' => $otpCode,
            'full_name' => $data->first_name." ".$data->last_name,
        ];

        $subject = "Transaction OTP (One Time Password)";

        // here mail to will be dynamic $data->email

        Mail::to('devsdm2022@gmail.com')->send(new SendOTPMail($details, $subject));
        return true;
    }

    public function sendOtpMail(Request $req)
    {
        try {
            $userDtl = new UserDetails();
            $getUserDtl = $userDtl->getUserByIdN($req["id"]);
            $n = 6;
            $otp = $this->generateNumericOTP($n);
            $sentMail = $this->sendOtpEmail($otp, $getUserDtl);
            $rowAffected = $userDtl->updateOtp($req["id"], $otp);

            if($rowAffected > 0)
            {
                echo json_encode(["status" => true]);
                exit;
                die();
            }
            echo json_encode(["status" => false]);
            exit;
            die();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function balanceWithdrawalRequest(Request $req)
    {
        try {
            $rules = [
                'user_id'           => 'required',
                'withdrawal_amount' => 'required',
                'vat_amount'        => 'required',
                'tax_amount'        => 'required',
                'total_amount'      => 'required',
                'otp'               => 'required'
            ];
        
            $customMessages = [
                'user_id.required'              => 'User id is required',
                'withdrawal_amount.required'    => 'Withdrawal amount is required',
                'vat_amount.required'           => 'Vat amount is required',
                'tax_amount.required'           => 'Tax amount is required',
                'total_amount.required'         => 'total amount is required',
                'otp.required'                  => 'OTP is required'
            ];
        
            $this->validate($req, $rules, $customMessages);

            $data = $req->except(['_token']);

            $userDtl = new UserDetails();
            $userOtp = $userDtl->getOtpByUserID($data['user_id']);
            // check OTP
            if($userOtp->otp == (int) $data['otp'])
            {
                if(isset($data["banking_type"]))
                {
                    if($data["banking_type"] == 1)
                    {
                        // for mobile banking
                        $mbData = [
                            'operator' => $data["operator"],
                            'mbl_ac_no' => $data["mbl_ac_no"],
                            'ac_type' => $data["ac_type"],
                        ];
                    }

                    if($data["banking_type"] == 2)
                    {
                        // for business/branch banking
                        $mbData = [
                            'acc_title' => $data["acc_title"],
                            'ac_no' => $data["ac_no"],
                            'bank_name' => $data["bank_name"],
                            'branch_name' => $data["branch_name"],
                        ];
                    }

                }

                $data["withdrawal_details"] = json_encode($mbData);
                
                $response = $userDtl->sendWithdrawalRequestForAuthorization($data);

                if($response)
                {
                    return redirect('/withdrawal-request');
                }
                return redirect()->back()->with('error', 'Something wrong. Please try after sometime!');
                
            }else{
                return redirect()->back()->with('error', 'OTP does not match!');
            }
            exit;

        } catch (\Exception $e) {
            return $e->getMessage();
        }        

    }

    public function getWithdrawalRequest()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }

        $id = Auth::user()->id;
        $userDtl = new UserDetails();
        $wRequests = $userDtl->getWithdrawalRequestByID($id);

        return view('admin_panel.withdrawal_request', ["wRequests" => $wRequests]);
    }

    public function getAllWithdrawalRequests()
    {
        if(Auth::user()->user_role != 0 && Auth::user()->user_role != 1){
            return redirect('/dashboard');
        }
        $userDtl = new UserDetails();
        $wRequests = $userDtl->getAllWithdrawalRequests();

        return view('admin_panel.withdrawal_requests', ["wRequests" => $wRequests]);
    }

    public function updateWithdrawalRequest(Request $req){
        $userDtl = new UserDetails();

        $getReqAmount = $userDtl->getWithdrawalRequestByID($_POST["user_id"]);
        $wRequests = $userDtl->updateWithdrawalRequest($_POST);

        if((int) $_POST["req_val"] = 1 && $wRequests > 0){
            $wd_amt = $getReqAmount[0]->withdrawal_amount;
                
            $getAccountNumber = $userDtl->getUserAccountDetailsByUserid($_POST['user_id']);
            $getAccountBalance = $userDtl->getAccountBalanceById($_POST['user_id']);
            
            if(is_null($getAccountBalance)){
                $totalCredit = 0;
            }else{
                $totalCredit = (isset($getAccountBalance->totalCredit) && $getAccountBalance->totalCredit == null)?0:$getAccountBalance->totalCredit;
            }

            if(is_null($getAccountBalance)){
                $totalDebit = 0;
            }else{
                $totalDebit = (isset($getAccountBalance->totalDebit) && $getAccountBalance->totalDebit == null)?0:$getAccountBalance->totalDebit;
            }

            $balance = (number_format((float)$totalCredit, 2, '.', '') - (number_format((float)$totalDebit, 2, '.', '') + number_format((float)$wd_amt, 2, '.', '')));
            
            $txn_data = [
                'for' => 'withdrawal',
                'ref_id' => $_POST['user_id'],
                'account_number' => $getAccountNumber->account_number,
                'trxn_id' => mt_rand(10000000, 99999999),
                'debit' => number_format((float) $wd_amt, 2, '.', ''),
                'balance' => number_format((float) $balance, 2, '.', ''),
                'ref_from' => 'Balance Withdrawal'
            ];

            event(new TransactionEvent($txn_data));
        }

        echo json_encode(['status' => "success", 'message' => "Request update successfully"]);
        exit;
        die;
    }
}
