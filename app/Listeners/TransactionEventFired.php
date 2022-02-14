<?php

namespace App\Listeners;

use App\Events\TransactionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\DB;

class TransactionEventFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionEvent  $event
     * @return void
     */
    public function handle(TransactionEvent $event)
    {
        if(isset($event->trxn_data["for"]))
        {
            if($event->trxn_data["for"] == "reference_bonus")
            {
                $trxn_data = [
                    'user_id' => $event->trxn_data["ref_id"],
                    'account_no' => $event->trxn_data["account_number"],
                    'trxn_details' => "Reference bonus from ".$event->trxn_data["ref_from"],
                    'trxn_id' => $event->trxn_data["trxn_id"],
                    'credit' => $event->trxn_data["credit"],
                    'balance' => $event->trxn_data["balance"],
                    'created_by' => "system",
                    'created_at' => date('Y-m-d H:i:s'),
                ];

            }
            else if($event->trxn_data["for"] == "generation_bonus")
            {
                $trxn_data = [
                    'user_id' => $event->trxn_data["ref_id"],
                    'account_no' => $event->trxn_data["account_number"],
                    'trxn_details' => $event->trxn_data["ref_from"],
                    'trxn_id' => $event->trxn_data["trxn_id"],
                    'credit' => $event->trxn_data["credit"],
                    'balance' => $event->trxn_data["balance"],
                    'created_by' => "system",
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            else if($event->trxn_data["for"] == "golden_bonus")
            {
                $trxn_data = [
                    'user_id' => $event->trxn_data["ref_id"],
                    'account_no' => $event->trxn_data["account_number"],
                    'trxn_details' => $event->trxn_data["ref_from"],
                    'trxn_id' => $event->trxn_data["trxn_id"],
                    'credit' => $event->trxn_data["credit"],
                    'balance' => $event->trxn_data["balance"],
                    'created_by' => "system",
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            else if($event->trxn_data["for"] == "weekly_bonus")
            {
                $trxn_data = [
                    'user_id' => $event->trxn_data["ref_id"],
                    'account_no' => $event->trxn_data["account_number"],
                    'trxn_details' => $event->trxn_data["ref_from"],
                    'trxn_id' => $event->trxn_data["trxn_id"],
                    'credit' => $event->trxn_data["credit"],
                    'balance' => $event->trxn_data["balance"],
                    'is_weekly_bonus' => 1,
                    'created_by' => "system",
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            else if($event->trxn_data["for"] == "withdrawal")
            {
                $trxn_data = [
                    'user_id' => $event->trxn_data["ref_id"],
                    'account_no' => $event->trxn_data["account_number"],
                    'trxn_details' => $event->trxn_data["ref_from"],
                    'trxn_id' => $event->trxn_data["trxn_id"],
                    'debit' => $event->trxn_data["debit"],
                    'balance' => $event->trxn_data["balance"],
                    'created_by' => "system",
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            
            DB::table('income_statements')->insert($trxn_data);
        }
    }
}
