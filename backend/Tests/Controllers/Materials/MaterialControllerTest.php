<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Materials\MaterialController;
use App\Models\Materials\MaterialModel;
use Exception;

class MaterialControllerTest extends TestCase
{
    private $materialModelMock;
    private $materialController;

    protected function setUp(): void
    {
        // Mock MaterialModel
        $this->materialModelMock = $this->createMock(MaterialModel::class);

        // Inject the mock MaterialModel into MaterialController
        $this->materialController = new MaterialController($this->materialModelMock);
    }

    public function testGetIdByFileNameSuccess()
    {
        // Arrange: Mock findByFileName to return a material with an ID
        $fileName = 'example-file.pdf';
        $expectedMaterial = ['id' => 1];
        $this->materialModelMock->method('findByFileName')
                                ->with($fileName)
                                ->willReturn($expectedMaterial);

        // Act: Call getIdByFileName on the controller
        $result = $this->materialController->getIdByFileName($fileName);

        // Assert: Check that the result matches the expected ID
        $this->assertEquals(1, $result);
    }

    public function testGetIdByFileNameNotFound()
    {
        // Arrange: Mock findByFileName to return null (material not found)
        $fileName = 'nonexistent-file.pdf';
        $this->materialModelMock->method('findByFileName')
                                ->with($fileName)
                                ->willReturn(null);

        // Act: Call getIdByFileName on the controller
        $result = $this->materialController->getIdByFileName($fileName);

        // Assert: Check that the result is null due to material not being found
        $this->assertNull($result);
    }

    public function testGetMaterialByIdSuccess()
    {
        // Arrange: Mock findById to return a material
        $id = 1;
        $expectedMaterial = ['id' => $id, 'title' => 'Test Material'];
        $this->materialModelMock->method('findById')
                                ->with($id)
                                ->willReturn($expectedMaterial);

        // Act: Call getMaterialById on the controller
        $result = $this->materialController->getMaterialById($id);

        // Assert: Check that the result matches the expected material
        $this->assertEquals($expectedMaterial, $result);
    }

    public function testGetMaterialByIdNotFound()
    {
        // Arrange: Mock findById to return null (material not found)
        $id = 999;
        $this->materialModelMock->method('findById')
                                ->with($id)
                                ->willReturn(null);

        // Act: Call getMaterialById on the controller
        $result = $this->materialController->getMaterialById($id);

        // Assert: Check that the result is null due to material not being found
        $this->assertNull($result);
    }

    public function testIncrementMaterialDownloadsSuccess()
    {
        // Arrange: Mock incrementDownloads to return true (successful increment)
        $id = 1;
        $this->materialModelMock->method('incrementDownloads')
                                ->with($id)
                                ->willReturn(true);

        // Act: Call incrementMaterialDownloads on the controller
        $result = $this->materialController->incrementMaterialDownloads($id);

        // Assert: Check that the result is true (downloads incremented successfully)
        $this->assertTrue($result);
    }

    public function testIncrementMaterialDownloadsFailure()
    {
        // Arrange: Mock incrementDownloads to return false (failed increment)
        $id = 1;
        $this->materialModelMock->method('incrementDownloads')
                                ->with($id)
                                ->willReturn(false);

        // Act: Call incrementMaterialDownloads on the controller
        $result = $this->materialController->incrementMaterialDownloads($id);

        // Assert: Check that the result is false (failed to increment downloads)
        $this->assertFalse($result);
    }

    public function testGetAndIncrementDownloadsSuccess()
    {
        // Arrange: Mock findById and incrementDownloads for success
        $id = 1;
        $expectedMaterial = ['id' => $id, 'title' => 'Test Material'];
        $this->materialModelMock->method('findById')
                                ->with($id)
                                ->willReturn($expectedMaterial);
        $this->materialModelMock->method('incrementDownloads')
                                ->with($id)
                                ->willReturn(true);

        // Act: Call getAndIncrementDownloads on the controller
        $result = $this->materialController->getAndIncrementDownloads($id);

        // Assert: Check that the result matches the expected material
        $this->assertEquals($expectedMaterial, $result);
    }

    public function testGetAndIncrementDownloadsFailure()
    {
        // Arrange: Mock findById to return null (material not found)
        $id = 999;
        $this->materialModelMock->method('findById')
                                ->with($id)
                                ->willReturn(null);

        // Act: Call getAndIncrementDownloads on the controller
        $result = $this->materialController->getAndIncrementDownloads($id);

        // Assert: Check that the result is null due to material not being found
        $this->assertNull($result);
    }
}
