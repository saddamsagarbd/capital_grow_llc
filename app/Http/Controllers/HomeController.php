<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\UserDetails;
use App\Models\IncomeStatement;
use App\Models\ReferenceCount;
use App\Models\ShareBonus;
use App\Models\PaymentConfirmRequest;

use App\Mail\SendPaymentConfirmMail;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Events\TransactionEvent;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }

        $user_dtl = new UserDetails();
        $id = Auth::user()->user_id;
        if(Auth::user()->user_role == 2){
            $id = Auth::user()->id;
        }
        $totalBalance = $user_dtl->getAccountBalanceById($id);
        
        $totalReferal = $credit = $debit = 0;
        $getGoldenBoardDtl = [];
        $getTofiveGoldenBoardMembers = [];
        $getTofiveDiamondBoardMembers = [];
        
        if(!is_null($totalBalance)){
            $credit = ($totalBalance->totalCredit == null)?'0.00':$totalBalance->totalCredit;
            $debit = ($totalBalance->totalDebit == null)?'0.00':$totalBalance->totalDebit;
        }
        $balance = (number_format((float) $credit, '2', '.', '') - number_format((float) $debit, '2', '.', ''));
        
        if(Auth::user()->user_role == 2){
            $totalReferal = $user_dtl->getTotalRefarals($id);
            $getGoldenBoardDtl = $user_dtl->getGoldenBoardDtlByUserId($id);
            $getTofiveGoldenBoardMembers = $user_dtl->getTofiveGoldenBoardMembers();
            $getTofiveDiamondBoardMembers = $user_dtl->getTofiveDiamondBoardMembers();
        }  
        
        return view('admin_panel.dashboard', [
            'balance' => $balance, 
            'totalReferal' => isset($totalReferal->total_referal)?$totalReferal->total_referal:0,
            'getGoldenBoardDtl' => $getGoldenBoardDtl,
            'getTofiveGoldenBoardMembers' => !empty($getTofiveGoldenBoardMembers)?$getTofiveGoldenBoardMembers:[],
            'getTofiveDiamondBoardMembers' => !empty($getTofiveDiamondBoardMembers)?$getTofiveDiamondBoardMembers:[],
        ]);
    }

    public function confirmPayment()
    {
        $paymentReqStatus = DB::table("payment_confirm_requests")
                            ->where("user_id", Auth::user()->id)
                            ->select("payment_request")
                            ->first();

        return view('admin_panel.payment_confirm_form', [
            'payment_request' => (isset($paymentReqStatus->payment_request))?(int) $paymentReqStatus->payment_request:null
        ]);
    }

    public function file_upload($image)
    {
        $filename = 'payment_slip_' . time() . '.' . $image[0]->getClientOriginalExtension();
        
        $upload_path = base_path('public');

        $path = $image[0]->move(

            $upload_path.'/uploads/payment_slip/', $filename

        );
        
        return $filename;
    }

    public function makePaymentConfirm(Request $req)
    {
        try {
            $rules = [
                'user_id'          => 'required',
                'username'          => 'required',
                'transaction_no'    => 'required',
                'paid_amount'       => 'required',
                'payment_date'      => 'required',
                'payment_option'    => 'required',
                'pay_to_ac'         => 'required'
            ];
        
            $customMessages = [
                'user_id.required'          => 'You must enter User ID',
                'username.required'         => 'You must enter Username',
                'transaction_no.required'   => 'You must enter Transaction no',
                'paid_amount.required'      => 'You must enter Paid amount',
                'payment_date.required'     => 'You must enter Payment date',
                'payment_option.required'   => 'You must select payment option from the list',
                'pay_to_ac.required'        => 'You must enter account number where you deposite'
            ];
        
            $this->validate($req, $rules, $customMessages);

            $filename = "";

            if ($req->hasfile('img')) {
                $filename = $this->file_upload($req->file('img'));
            }

            $filename = "";

            $data = $req->except(["_token", "img"]);
            $data['receipt_name'] = $filename;

            $data = array_filter($data);

            if(PaymentConfirmRequest::create($data)){
                return redirect()->back()->with('success', 'Your request has been sent to concerned authority!');
            }
            else{
                return redirect()->back()->with('error', 'Something wrong, Please try after sometimes!');
            }
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function getPendingUsers()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $user_profile = new UserDetails();
        $users = $user_profile->getPendingUsersList();
        return view('admin_panel.pending_list', ['users' => $users]);
    }

    public function getActiveUsers()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $user_profile = new UserDetails();
        $users = $user_profile->getActiveUsersList();
        return view('admin_panel.active_list', ['users' => $users]);
    }

    public function getUserById($id)
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $user_profile = new UserDetails();
        $userDtl = $user_profile->getUserByIdN($id);
        return view('admin_panel.user.userForm', ['userDtl' => $userDtl]);
    }

    public function UpdateRegisteredUserInfo(Request $request)
    {
        try {
            $user_dtls = new UserDetails();
            $inst = new IncomeStatement();

            /**
             * Update user profile with status
            */

            $data = $request->except("_token");
            $response = $user_dtls->updateUserDetails($data);

            /**
             * Keep Organization's Income
            */
            if($response == true){
                $orgInfo = $user_dtls->getUserByIdN(Auth::user()->id);
                $orgAccInfo = $user_dtls->getAccountDetailsByUserId($orgInfo->user_id);
                $orgAccBalance = $user_dtls->getAccountBalanceById($orgInfo->user_id);
                $userAccInfo = $user_dtls->getAccountDetailsByUserId($data["user_id"]);

                if(is_null($orgAccBalance)){
                    $orgAccBalance = 0;
                }

                $balance = (number_format((float)$orgAccBalance, 2, '.', '') + number_format((float)$data["registration_balance"], 2, '.', ''));


                $instData = [
                    'user_id' => $orgInfo->user_id,
                    'account_no' => $orgAccInfo->account_number,
                    'trxn_id' => mt_rand(10000000, 99999999),
                    'trxn_details' => "Registration Balance Deposite. Ref:".$userAccInfo->account_title,
                    'credit' => number_format((float)$data["registration_balance"], 2, '.', ''),
                    'balance' => number_format((float)$balance, 2, '.', ''),
                    'created_by' => Auth::user()->id,
                    'created_at' => date("Y-m-d H:i:s")
                ];

                $inst_response = $inst->makeTransaction($instData);
                if($inst_response){
                    return redirect('/dashboard');
                }

            }
            
            return Redirect::back()->withErrors(['msg' => 'Something went wrong! Please try again']);
        
        } catch (\Exception $e) {
        
            return $e->getMessage();
        }        
    }

    public function sendEmail($data)
    {
        // send company account details via Mail
        $details = [
            'title' => "Payment Confirm",
            'body' => "Bkash: 01994282802(personal)",
            'full_name' => $data->first_name." ".$data->last_name,
        ];

        $subject = "Payment Confirm";

        Mail::to('devsdm2022@gmail.com')->send(new SendPaymentConfirmMail($details, $subject));
        return true;
    }

    /**
     * Param $ref_username
     * return bool
    */

    public function increaseReferalUsers($ref_username)
    {
        $user_profile = new UserDetails();
        $ref_count = new ReferenceCount();
        
        $getReferenceUserid = $user_profile->getUserByUsername($ref_username);
        $response = $ref_count->incrementRefarals($getReferenceUserid->id);
        if($response){
            return true;
        }
        return false;
    }


    /**
     * Param $ref_username & $new_userid
     * return bool
    */

    public function shareReferenceBonus($ref_username, $new_userid)
    {
        $user_profile = new UserDetails();
        $shareBonus = new ShareBonus();
        $ref_amt = 6;

        $getReferenceUserid = $user_profile->getUserByUsername($ref_username);

        $getNewUserDtl = $user_profile->getUserByIdN($new_userid);
        $ref_from = $getNewUserDtl->first_name.' '.$getNewUserDtl->last_name.' ('. $getNewUserDtl->username .')';
        $getAccountNumber = $user_profile->getUserAccountDetailsByUserid($getReferenceUserid->user_id);
        $getAccountBalance = $user_profile->getAccountBalanceById($getReferenceUserid->id);
        
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

        $balance = ((number_format((float)$totalCredit, 2, '.', '') - (number_format((float)$totalDebit, 2, '.', ''))) + number_format((float)$ref_amt, 2, '.', ''));
        
        $txn_data = [
            'for' => 'reference_bonus',
            'ref_id' => $getReferenceUserid->id,
            'account_number' => $getAccountNumber->account_number,
            'trxn_id' => mt_rand(10000000, 99999999),
            'credit' => number_format($ref_amt, 2, '.', ''),
            'balance' => number_format($balance, 2, '.', ''),
            'ref_from' => $ref_from
        ];

        event(new TransactionEvent($txn_data));

        return true;

    }

    public function getGenerationStageAndBonus($gen)
    {
        $stage = "";
        $bonus = "";
        switch ($gen) {
            case 1:
                $stage = "1st";
                $bonus = "4";
                break;

                case 2:
                    $stage = "2nd";
                    $bonus = "3";
                    break;

                    case 3:
                        $stage = "3rd";
                        $bonus = "2";
                        break;

                        case 4:
                            $stage = "4th";
                            $bonus = "1";
                            break;
                            
                            default:
                                $stage = "1st";
                                $bonus = "4";
        }
        return ["stage" => $stage, "bonus" => $bonus];

    }

    public function generationIncentive($id, $gen=1)
    {
        $userDtl = new UserDetails();
        $up_pl_id = $userDtl->getUserPlacementIdByIdN($id);

        if(isset($up_pl_id->placement_id) && $up_pl_id->placement_id != NULL)
        {
            $getReferenceUserid = $userDtl->getUserByIdN($up_pl_id->placement_id);

            $getGenerationStage = $this->getGenerationStageAndBonus($gen);

            $ref_from = $getGenerationStage['stage']." generation bonus";
            $getAccountNumber = $userDtl->getUserAccountDetailsByUserid($getReferenceUserid->user_id);
            $getAccountBalance = $userDtl->getAccountBalanceById($getReferenceUserid->id);

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
    
            $balance = ((number_format((float)$totalCredit, 2, '.', '') - (number_format((float)$totalDebit, 2, '.', ''))) + number_format((float)$getGenerationStage['bonus'], 2, '.', ''));
            

            if($gen <= 4){
                $txn_data = [
                    'for' => 'generation_bonus',
                    'ref_id' => (int) $up_pl_id->placement_id,
                    'account_number' => $getAccountNumber->account_number,
                    'trxn_id' => mt_rand(10000000, 99999999),
                    'credit' => number_format((float) $getGenerationStage['bonus'], 2, '.', ''),
                    'balance' => number_format((float) $balance, 2, '.', ''),
                    'ref_from' => $ref_from
                ];
                event(new TransactionEvent($txn_data));

                $gen +=1;

                $this->generationIncentive($up_pl_id->placement_id, $gen);
            }        
        }
        
        return true;
    }

    
    public function promoteUserToGoldenBoard($ref_username)
    {
        $user_profile = new UserDetails();
        $getReferenceUserid = $user_profile->getUserByUsername($ref_username);
        $countTotalReferal = $user_profile->getTotalreferalUsersByPlacementId($getReferenceUserid->id);
        
        if($countTotalReferal->total_referal >= 2 && $countTotalReferal->total_referal <= 5){
            $userexistongoldenboard = $user_profile->isExistOnGoldenBoard($getReferenceUserid->id, "golden_board");
            
            if(is_null($userexistongoldenboard))
            {
                $promotUserToGoldenBoard = $user_profile->switchOnGoldenBoard($getReferenceUserid->id, "golden_board");
                
                if($promotUserToGoldenBoard)
                {
                    $promotUserToDiamondBoard = $user_profile->switchOnDiamondBoard("diamond_board");
                    if(isset($promotUserToDiamondBoard["status"]))
                    {
                        $gb_amt = 370;
                        $gb_id = $promotUserToDiamondBoard["top_id"];
                        $getAccountNumber = $user_profile->getUserAccountDetailsByUserid($gb_id);
                        $getAccountBalance = $user_profile->getAccountBalanceById($gb_id);
                        
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

                        $balance = ((number_format((float)$totalCredit, 2, '.', '') - (number_format((float)$totalDebit, 2, '.', ''))) + number_format((float)$gb_amt, 2, '.', ''));
                        
                        $txn_data = [
                            'for' => 'golden_bonus',
                            'ref_id' => $gb_id,
                            'account_number' => $getAccountNumber->account_number,
                            'trxn_id' => mt_rand(10000000, 99999999),
                            'credit' => number_format($gb_amt, 2, '.', ''),
                            'balance' => number_format($balance, 2, '.', ''),
                            'ref_from' => 'Golden board bonus shared'
                        ];
                
                        event(new TransactionEvent($txn_data));
                    }
                }
            }
        }

        return true;
    }

    public function confirmPaymentRequest(Request $request)
    {
        try {
            $data = $request->except(['_token']);
            $id = $data["user_id"];
            $userdtl = new UserDetails();
            
            $updateUserStatus = $userdtl->updateUserDetails($id);
            $updatePaymentStatus = $userdtl->updatePaymentRequestStatus($id);
            
            if($updateUserStatus && $updatePaymentStatus > 0)
            {
                $data = $userdtl->getUserByIdN($id);
                $ref_username = $userdtl->getPlacementReferenceUsernameByIdN($data->reference_id);
                $plc_username = $userdtl->getPlacementReferenceUsernameByIdN($data->placement_id);
                $this->sendEmail($data);

                if(!is_null($data->reference_id) && !is_null($data->placement_id))
                {
                    // Referal User count
                    $this->increaseReferalUsers($plc_username->username);

                    // Share reference bonus

                    $this->shareReferenceBonus($ref_username->username, $id);

                    // Share generation bonus

                    $this->generationIncentive($id);

                    // promote user level
                    $this->promoteUserToGoldenBoard($plc_username->username);
                }

                echo json_encode(['status' => true]);
                exit;
                die;
            }
            echo json_encode(['status' => false]);
            exit;
            die;
        } catch (\Exception $e) {
            return $e->getMessage();
        }  

    }

    public function manageAccounts()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $userDtl = new UserDetails();
        $id = Auth::user()->id;
        $acInfo = $userDtl->getUserAccountsInfo($id);
        return view('admin_panel.manage_accounts', [ 'acInfo' => $acInfo]);
    }

    public function addAccountInfoFrom()
    {
        return view('admin_panel.add_account_info_form');
    }

    public function saveAccountInfo(Request $req)
    {
        try {
            $rules = [
                'banking_type'      => 'required'
            ];
        
            $customMessages = [
                'banking_type.required'     => 'You must select type of banking from dropdown list'
            ];
        
            $this->validate($req, $rules, $customMessages);
            
            $data = $req->except(['_token']);

            $data["user_id"] = Auth::user()->id;
            $data["created_by"] = Auth::user()->id;
            $data["created_at"] = date('Y-m-d H:i:s');

            $userDtl = new UserDetails();
            $saveAcInfo = $userDtl->saveAccountInfo(array_filter($data), 'user_account_information');
            if($saveAcInfo)
            {
                return Redirect::to('/manage-accounts')->with(['status' => 'success','message' => 'Account Information successfully saved!']);
            }
            return Redirect::to('/')->with(['status' => 'error','message' => 'Something wrong please try again or after sometime!']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
