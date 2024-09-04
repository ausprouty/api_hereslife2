<?php

use PHPUnit\Framework\TestCase;
use App\Services\UserMaterialService;
use App\Controllers\Materials\MaterialController;
use App\Controllers\People\ChampionController;
use App\Controllers\Materials\DownloadController;

class UserMaterialServiceTest extends TestCase
{
    protected $materialControllerMock;
    protected $championControllerMock;
    protected $downloadControllerMock;
    protected $service;

    /**
     * Set up the mocks and the service instance before each test.
     */
    protected function setUp(): void
    {
        // Mock dependencies
        $this->materialControllerMock = $this->createMock(MaterialController::class);
        $this->championControllerMock = $this->createMock(ChampionController::class);
        $this->downloadControllerMock = $this->createMock(DownloadController::class);

        // Instantiate the service with mocked controllers
        $this->service = new UserMaterialService(
            $this->materialControllerMock,
            $this->championControllerMock,
            $this->downloadControllerMock
        );
    }

    /**
     * Test handleUserMaterialDownload when material is not found.
     */
    public function testHandleUserMaterialDownloadMaterialNotFound()
    {
        // Input data
        $data = ['file' => 'testfile.pdf', 'selected_mail_lists' => 'newsletter'];

        // Mock method behavior
        $this->materialControllerMock
            ->expects($this->once())
            ->method('getIdByFileName')
            ->with('testfile.pdf')
            ->willReturn(null); // Material not found

        // Execute the method
        $result = $this->service->handleUserMaterialDownload($data);

        // Assert the expected result (file not found)
        $expectedResult = json_encode(['success' => false, 'message' => 'File not found']);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test handleUserMaterialDownload when user details update fails.
     */
    public function testHandleUserMaterialDownloadUserUpdateFails()
    {
        // Input data
        $data = ['file' => 'testfile.pdf', 'selected_mail_lists' => 'newsletter'];

        // Mock method behavior
        $this->materialControllerMock
            ->expects($this->once())
            ->method('getIdByFileName')
            ->with('testfile.pdf')
            ->willReturn(1); // Material found
        
        $this->materialControllerMock
            ->expects($this->once())
            ->method('getAndIncrementDownloads')
            ->with(1);

        $this->championControllerMock
            ->expects($this->once())
            ->method('updateChampionFromForm')
            ->willReturn(null); // User update fails

        // Execute the method
        $result = $this->service->handleUserMaterialDownload($data);

        // Assert the expected result (user update fails)
        $expectedResult = json_encode(['success' => false, 'message' => 'Failed to update user details']);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test handleUserMaterialDownload with successful processing.
     */
    public function testHandleUserMaterialDownloadSuccess()
    {
        // Input data
        $data = [
            'file' => 'testfile.pdf',
            'selected_mail_lists' => 'newsletter,tips'
        ];

        // Mock method behavior
        $this->materialControllerMock
            ->expects($this->once())
            ->method('getIdByFileName')
            ->with('testfile.pdf')
            ->willReturn(1); // Material found

        $this->materialControllerMock
            ->expects($this->once())
            ->method('getAndIncrementDownloads')
            ->with(1);

        $this->championControllerMock
            ->expects($this->once())
            ->method('updateChampionFromForm')
            ->willReturn(2); // User ID

        $this->championControllerMock
            ->expects($this->once())
            ->method('updateLastDownloadDate')
            ->with(2);

        $this->downloadControllerMock
            ->expects($this->once())
            ->method('createDownloadRecord')
            ->with([
                'champion_id' => 2,
                'file_name' => 'testfile.pdf',
                'file_id' => 1,
                'requested_tips' => time(),
            ]);

        // Execute the method
        $result = $this->service->handleUserMaterialDownload($data);

        // Assert the expected result (successful download)
        $expectedResult = json_encode(['success' => true, 'file_url' => URL_RESOURCES . 'testfile.pdf']);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test splitMailingLists method.
     */
    public function testSplitMailingLists()
    {
        $data = 'newsletter,tips';

        // Call the protected method using reflection
        $reflection = new \ReflectionClass(UserMaterialService::class);
        $method = $reflection->getMethod('splitMailingLists');
        $method->setAccessible(true);

        // Execute and assert
        $result = $method->invokeArgs($this->service, [$data]);
        $this->assertEquals(['newsletter' => true, 'tips' => true], $result);
    }
}
