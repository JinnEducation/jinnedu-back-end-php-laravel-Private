<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\TutorFinance;
use App\Models\Conference;
use App\Models\ConferenceComplaint;
use App\Models\User;
use App\Models\UserWallet;

class TransferTutorFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Tutor Fees';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    
        $tutor_finances = TutorFinance::where('status', 'pending')
                                    ->where('class_date', '<', Carbon::now()->subHours(2))
                                    ->get();

        foreach ($tutor_finances as $tutor_finance) {

            // Check complaint
            $check_complaint = $this->checkComplaintForTransfer($tutor_finance);

            if (!$check_complaint) {
                $this->addTutorTransferToHisWallet($tutor_finance->tutor_id, $tutor_finance->fee);
                $tutor_finance->status = 'transferred';
                $tutor_finance->save();
            }

        }
    }

        
    private function addTutorTransferToHisWallet($tutor_id, $fee){

        $user = User::find($tutor_id);
        $wallet = $user->wallets()->first();
        if(!$wallet) {
            $wallet = new UserWallet;
            $wallet->user_id = $tutor_id;
            $wallet->balance = $fee;
            $wallet->save();
        } else {
            $wallet->balance += $fee;
            $wallet->save();
        }
        
    }

    private function checkComplaintForTransfer(TutorFinance $tutor_finance)
    {
        switch ($tutor_finance->ref_type) {
            case 1:
                $groupClassConferenceIds = Conference::where('ref_type', 1)
                    ->where('ref_id', $tutor_finance->ref_id)
                    ->pluck('id')->toArray();
                return ConferenceComplaint::whereIn('conference_id', $groupClassConferenceIds)->count();
            case 4:
                $conference = Conference::where('order_id', $tutor_finance->order_id)->first();
                return ConferenceComplaint::where('conference_id', $conference->id)->count();
            default:
                Log::warning("Unexpected ref_type in TutorFinance: " . $tutor_finance->ref_type);
                return false;
        }
    }
}
