<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Emails\EmailListMemberController;
use App\Models\Emails\EmailListMemberModel;
use App\Models\Emails\EmailModel;
use App\Models\Emails\EmailQueModel;

class EmailNextTipsTest extends TestCase
{
    protected $emailListMemberModel;
    protected $emailModel;
    protected $emailQueModel;
    protected $emailListMemberController;

    protected function setUp(): void
    {
        // Create mocks for models and other dependencies
        $this->emailListMemberModel = $this->createMock(EmailListMemberModel::class);
        $this->emailModel = $this->createMock(EmailModel::class);
        $this->emailQueModel = $this->createMock(EmailQueModel::class);

        // Create the controller instance with the mocked models
        $this->emailListMemberController = $this->getMockBuilder(EmailListMemberController::class)
            ->setConstructorArgs([$this->emailListMemberModel, $this->emailModel, $this->emailQueModel])
            ->onlyMethods(['findTipForSeries', 'queTipForMember'])
            ->getMock();
    }

    public function testFindNextRequestsForTips()
    {
        // Test data simulating members with different tip states
        $nextRequests = [
            [
                'id' => 1,
                'list_name' => 'tracts',
                'champion_id' => 101,
                'last_tip_sent' => 1, // Tip 1 sent
                'last_tip_sent_time' => strtotime('-6 days') // 6 days ago (too early)
            ],
            [
                'id' => 2,
                'list_name' => 'tracts',
                'champion_id' => 102,
                'last_tip_sent' => 4, // Tip 4 sent
                'last_tip_sent_time' => strtotime('-10 days') // 10 days ago (eligible for next tip)
            ],
            [
                'id' => 3,
                'list_name' => 'tracts',
                'champion_id' => 103,
                'last_tip_sent' => 10, // Max tip reached
                'last_tip_sent_time' => strtotime('-15 days') // 15 days ago
            ]
        ];

        // Mock the method findNextRequestsForTips to return the test data
        $this->emailListMemberModel->method('findNextRequestsForTips')
            ->willReturn($nextRequests);

        // Mock the findTipForSeries to return valid email IDs for each tip
        $this->emailListMemberController
            ->method('findTipForSeries')
            ->willReturnCallback(function ($listName, $sequence) {
                if ($listName == 'tracts' && $sequence <= 10) {
                    return $sequence; // Tracts series goes up to 10 tips
                }
                return null; // No more tips available
            });

        // Mock the queTipForMember method to simulate queuing email
        $this->emailListMemberController
            ->method('queTipForMember')
            ->willReturnCallback(function ($championId, $listName, $nextTip) {
                // Member 2 should receive the next tip, Member 3 has finished all tips
                if ($championId == 102) {
                    return 'TRUE'; // Eligible for next tip
                }
                if ($championId == 103) {
                    return 'FALSE'; // No more tips
                }
                return 'FALSE'; // Default case (too early for Member 1)
            });

        // Mock the create method of emailQueModel for member 2
        $this->emailQueModel
            ->expects($this->once()) // Expect it to be called once for member 2
            ->method('create')
            ->with([
                'champion_id' => 102,
                'email_id' => 5 // Member 2's next tip is 5
            ]);

        // Mock the update method to ensure it gets called correctly
        $this->emailListMemberModel
            ->expects($this->exactly(2)) // Expect 2 updates: Member 2 and Member 3
            ->method('update')
            ->withConsecutive(
                [2, $this->callback(function ($data) {
                    // Check Member 2 gets the next tip (Tip 5)
                    return $data['last_tip_sent'] == 5 && isset($data['last_tip_sent_time']);
                })],
                [3, $this->callback(function ($data) {
                    // Check Member 3 is marked as finished
                    return isset($data['finished_all_tips']) && $data['finished_all_tips'] == 1;
                })]
            );

        // Run the processNextEmailTips method
        $this->emailListMemberController->processNextEmailTips();
    }
}
