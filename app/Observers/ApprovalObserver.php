<?php

namespace App\Observers;

use App\Models\Approval;
use App\Models\LeaveRequest;
use App\Models\Staff;

class ApprovalObserver
{
    /**
     * Handle the Approval "created" event.
     *
     * @param  \App\Models\Approval  $approval
     * @return void
     */
    public function created(Approval $approval)
    {
        // $leaveRequest = LeaveRequest::where('id', $approval->leave_request_id)->first();
        // $staff_id = $leaveRequest->staff_id;
        // $parent_id = Staff::where('id', $staff_id)->value('parent_id');

        // if ($approval->leave_request_id == $leaveRequest->id && $approval->status == 0) {
        //     return "Leave request approval updated!";
        // } else {
        //     $arr = [
        //         'leave_request_id' => $leaveRequest->id,
        //         'verifier_id' => $parent_id,
        //         'status' => 0
        //     ];
        //     $approval->create($arr);
        // }
    }


    /**
     * Handle the Approval "updated" event.
     *
     * @param  \App\Models\Approval  $approval
     * @return void
     */
    public function updated(Approval $approval)
    {
        //
    }

    /**
     * Handle the Approval "deleted" event.
     *
     * @param  \App\Models\Approval  $approval
     * @return void
     */
    public function deleted(Approval $approval)
    {
        //
    }

    /**
     * Handle the Approval "restored" event.
     *
     * @param  \App\Models\Approval  $approval
     * @return void
     */
    public function restored(Approval $approval)
    {
        //
    }

    /**
     * Handle the Approval "force deleted" event.
     *
     * @param  \App\Models\Approval  $approval
     * @return void
     */
    public function forceDeleted(Approval $approval)
    {
        //
    }
}
