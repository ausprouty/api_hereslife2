<?php
namespace App\Services\Emails;

use App\Models\Emails\EmailListMemberModel;

class EmailTipsService
{
    private $emailListMemberModel;

    // Inject the EmailListMemberModel via constructor
    public function __construct(EmailListMemberModel $emailListMemberModel)
    {
        $this->emailListMemberModel = $emailListMemberModel;
    }

    // Process new requests and send tips
    public function processNewRequestsForTips()
    {
        // Fetch new requests for tips from the model
        $newRequests = $this->emailListMemberModel->findNewRequestsForTips();

        // Loop through each new request and send the tips
        foreach ($newRequests as $request) {
            $this->queTipForMember($request);
            // Update the record to mark the tip as sent
            $data = [
                'last_tip_sent' => 1, // Current time
                'last_tip_sent_time' => Now(),
            ];
            $this->emailListMemberModel->update($request['id'], $data);
        }
    }

    
}
