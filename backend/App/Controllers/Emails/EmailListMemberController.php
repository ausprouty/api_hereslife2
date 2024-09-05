<?php

// Ensure that the models are included or autoloaded properly
require_once 'App/Models/Emails/EmailListMemberModel.php';
require_once 'App/Models/Emails/EmailModel.php';

class EmailListMemberController {

    private $emailListMemberModel;
    private $emailModel;

    // Inject both models via the constructor
    public function __construct(EmailListMemberModel $emailListMemberModel, EmailModel $emailModel)
    {
        $this->emailListMemberModel = $emailListMemberModel;
        $this->emailModel = $emailModel;
    }

    // Handle a request to find new requests for tips
    public function findNewRequestsForTips()
    {
        // Fetch new requests for tips
        $newRequests = $this->emailListMemberModel->findNewRequestsForTips();
        // Process each request
        foreach ($newRequests as $request) {
            $result = $this->queTipForMember($request);
            if ($result == 'TRUE') {
                // Update the last_tip_sent and last_tip_sent_time fields
                $data = [
                    'last_tip_sent' => 1, 
                    'last_tip_sent_time' => time(),  // Current timestamp
                ];
                // Update the member with the new data
                $this->emailListMemberModel->update($request['id'], $data);
            } else {
                error_log("Error sending email to member: " . $request['id']);
            }
        }
    }

    // Handle a request to find a tip for a series
    public function findTipForSeries($list_name, $sequence)
    {
        $tip = $this->emailModel->findIdForSeries($list_name, $sequence);
        return $tip;
    }

    // Placeholder for queTipForMember method
    public function queTipForMember($member)
    {
        // Implement the queuing logic here
        return 'TRUE'; // Return true for demo purposes
    }

    // Handle a request to get a member by ID
    public function getMemberById($id)
    {
        $member = $this->emailListMemberModel->findById($id);

        if ($member) {
            return json_encode($member); // Return as JSON response
        } else {
            return json_encode(['error' => 'Member not found'], JSON_PRETTY_PRINT); // Error handling
        }
    }

    // Handle a request to create a new member
    public function createMember($data)
    {
        $result = $this->emailListMemberModel->create($data);

        if ($result) {
            return json_encode(['message' => 'Member created successfully']);
        } else {
            return json_encode(['error' => 'Failed to create member']);
        }
    }

    // Handle a request to update a member
    public function updateMember($id, $data)
    {
        $result = $this->emailListMemberModel->update($id, $data);

        if ($result) {
            return json_encode(['message' => 'Member updated successfully']);
        } else {
            return json_encode(['error' => 'Failed to update member']);
        }
    }

    // Handle a request to delete a member
    public function deleteMember($id)
    {
        $result = $this->emailListMemberModel->delete($id);

        if ($result) {
            return json_encode(['message' => 'Member deleted successfully']);
        } else {
            return json_encode(['error' => 'Failed to delete member']);
        }
    }
}
