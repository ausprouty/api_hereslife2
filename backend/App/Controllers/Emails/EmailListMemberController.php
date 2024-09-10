<?php
namespace App\Controllers\Emails;
// Ensure that the models are included or autoloaded properly
use App\Models\Emails\EmailListMemberModel;
use App\Models\Emails\EmailModel;
use App\Models\Emails\EmailQueModel;
use App\Services\Debugging;


class EmailListMemberController {

    private $emailListMemberModel;
    private $emailQueModel;
    private $emailModel;

    // Inject all models via the constructor
    public function __construct(EmailListMemberModel $emailListMemberModel, EmailModel $emailModel, EmailQueModel $emailQueModel)
    {
        $this->emailListMemberModel = $emailListMemberModel;
        $this->emailModel = $emailModel;
        $this->emailQueModel = $emailQueModel;

    }

    /**
     * Processes new requests for tips and sends tips to each member.
     *
     * This method fetches new requests for tips from the database, processes each request by
     * queuing a tip for the member, and updates the `last_tip_sent` and `last_tip_sent_time` 
     * fields in the database upon successful tip delivery.
     *
     * The requests array contains the following fields:
     * - 'id': The ID of the request.
     * - 'list_name': The name of the email list.
     * - 'champion_id': The ID of the member (champion).
     * - 'last_tip_sent': The sequence number of the last tip that was sent.
     * - 'last_tip_sent_time': The timestamp of when the last tip was sent.
     *
     * @return void
     */
    public function processNewEmailTips()
    {
        // Initialize the count of tips sent
        $count = 0;
        // Fetch new requests for tips
        $newRequests = $this->emailListMemberModel->findNewRequestsForTips();

        // Process each request
        foreach ($newRequests as $request) {
            $result = $this->queTipForMember($request['champion_id'], $request['list_name'], 1);

            if ($result == 'TRUE') {
                // Update the last_tip_sent and last_tip_sent_time fields
                $data = [
                    'last_tip_sent' => 1, 
                    'last_tip_sent_time' => NOW(),  // NOW() is used in MySQL queries to represent the current date and time in a format suitable for database operations.
                ];

                // Update the member with the new data
                $this->emailListMemberModel->update($request['id'], $data);
                $count++;
            } else {
                error_log("Error sending email to member: " . $request['id']);
            }
        }
        return $count;
    }
    /**
     * Processes the next request for tips and sends tips to each member.
     *
     * This method fetches nexts requests for tips from the database, processes each request by
     * queuing a tip for the member, and updates the `last_tip_sent` and `last_tip_sent_time` 
     * fields in the database upon successful tip delivery.  It may also set the `finished_all_tips`
     * field to true if the member has received all tips in the series.
     *
     * The requests array contains the following fields:
     * - 'id': The ID of the request.
     * - 'list_name': The name of the email list.
     * - 'champion_id': The ID of the member (champion).
     * - 'last_tip_sent': The sequence number of the last tip that was sent.
     * - 'last_tip_sent_time': The timestamp of when the last tip was sent.
     * - 'finished_all_tips': A flag indicating if the member has received all tips.
     *
     * @return void
     */
    public function processNextEmailTips()
    {
        // Initialize the count of tips sent
        $count = 0;

        // Fetch new requests for tips
        $nextRequests = $this->emailListMemberModel->findNextRequestsForTips();
        writeLog('processNextEmailTips-95', $nextRequests);
        // Process each request
        foreach ($nextRequests as $request) {
            writeLogAppend('processNextEmailTips-97', $request);
            

            $nextTip = $request['last_tip_sent'] + 1;
            $result = $this->queTipForMember($request['champion_id'], $request['list_name'], $nextTip);
            writeLogAppend('processNextEmailTips-108', $result);
            if ($result == 'TRUE') {
                // Update the last_tip_sent and last_tip_sent_time fields
                $data = [
                    'last_tip_sent' => $nextTip, 
                    'last_tip_sent_time' => NOW(),  //NOW() is used in MySQL queries to represent the current date and time in a format suitable for database operations.
                ];
                $count++;
            } else {
                $data = [
                    'finished_all_tips' => 1, 
                ];
            }
            writeLogAppend('processNextEmailTips-121', $data);
            // Update the member with the new data
            $this->emailListMemberModel->update($request['id'], $data);
        }
        return $count;
    }


    /**
     * Find the Email ID of a tip for a specific series and sequence number.
     *
     * This method retrieves the ID of a tip from a specific email series based on the
     * series name and the sequence number of the tip within that series.
     *
     * @param string $list_name The name of the email series.
     * @param int $sequence The sequence number of the tip in the series.
     * @return mixed The ID of the tip found, or null if no tip is found.
     */
    public function findTipForSeries($list_name, $sequence)
    {
        $tip = $this->emailModel->findIdForSeries($list_name, $sequence);
        return $tip;
    }

  

    public function queTipForMember($champion_id, $list_name, $sequence)
    {
        writeLogAppend('queTipForMember-149', "$champion_id, $list_name, $sequence");
        $email_id = $this->findTipForSeries($list_name, $sequence);
        writeLogAppend('queTipForMember-151', $email_id);
        if (!$email_id || $email_id == null) {
            return 'FALSE';
        }
        $data = [
            'champion_id' => $champion_id,
            'email_id' => $email_id
        ];
        writeLogAppend('queTipForMember-144', $data);
        $this->emailQueModel->create($data);
        return 'TRUE'; 
    }

   
    public function getMemberById($id)
    {
        $member = $this->emailListMemberModel->findById($id);

        if ($member) {
            return $member;
        } else {
            null;
        }
    }

    // Handle a request to create a new member
    public function createMember($data)
    {
        $result = $this->emailListMemberModel->create($data);

        if ($result) {
            return 'Member created successfully';
        } else {
            return 'Failed to create member';
        }
    }

    // Handle a request to update a member
    public function updateMember($id, $data)
    {
        $result = $this->emailListMemberModel->update($id, $data);

        if ($result) {
            return 'Member updated successfully';
        } else {
            return 'Failed to update member';
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
