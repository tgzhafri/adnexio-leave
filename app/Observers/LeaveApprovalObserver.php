<?php

namespace App\Observers;

use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use App\Models\Staff;

class LeaveApprovalObserver
{
    /**
     * Handle the LeaveApproval "created" event.
     *
     * @param  \App\Models\LeaveApproval  $leaveApproval
     * @return void
     */
    public function created(LeaveApproval $leaveApproval)
    {
        // $leaveRequest = LeaveRequest::where('id', $leaveApproval->leave_request_id)->first();
        // $staff_id = $leaveRequest->staff_id;
        // $parent_id = Staff::where('id', $staff_id)->value('parent_id');

        // if ($leaveApproval->leave_request_id == $leaveRequest->id && $leaveApproval->status == 0) {
        //     return "Leave request leave approval updated!";
        // } else {
        //     $arr = [
        //         'leave_request_id' => $leaveRequest->id,
        //         'verifier_id' => $parent_id,
        //         'status' => 0
        //     ];
        //     $leaveApproval->create($arr);
        // }
    }


    /**
     * Handle the LeaveApproval "updated" event.
     *
     * @param  \App\Models\LeaveApproval  $leaveApproval
     * @return void
     */
    public function updated(LeaveApproval $leaveApproval)
    {
        //
    }

    /**
     * Handle the LeaveApproval "deleted" event.
     *
     * @param  \App\Models\LeaveApproval  $leaveApproval
     * @return void
     */
    public function deleted(LeaveApproval $leaveApproval)
    {
        //
    }

    /**
     * Handle the LeaveApproval "restored" event.
     *
     * @param  \App\Models\LeaveApproval  $leaveApproval
     * @return void
     */
    public function restored(LeaveApproval $leaveApproval)
    {
        //
    }

    /**
     * Handle the LeaveApproval "force deleted" event.
     *
     * @param  \App\Models\LeaveApproval  $leaveApproval
     * @return void
     */
    public function forceDeleted(LeaveApproval $leaveApproval)
    {
        //
    }
}
