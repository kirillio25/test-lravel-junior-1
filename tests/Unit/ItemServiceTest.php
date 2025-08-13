<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Services\GoogleSheetService;
use App\Services\ItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    use RefreshDatabase;

    private $sheetServiceMock;
    private $itemService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sheetServiceMock = Mockery::mock(GoogleSheetService::class);
        $this->itemService = new ItemService($this->sheetServiceMock);

        Log::spy();
    }

    public function test_create_item_and_update_google_sheet()
    {
        $this->sheetServiceMock
            ->shouldReceive('updateSheet')
            ->once();

        $data = [
            'title' => 'Test Item',
            'description' => 'Description',
            'status' => 'Allowed',
        ];

        $this->itemService->create($data);

        $this->assertDatabaseHas('items', $data);
    }

    public function test_update_item_and_update_google_sheet()
    {
        $this->sheetServiceMock->shouldReceive('updateSheet')->once();

        $item = Item::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
            'status' => 'Prohibited',
        ];

        $this->itemService->update($item, $updateData);

        $this->assertDatabaseHas('items', array_merge(['id' => $item->id], $updateData));
    }

    public function test_delete_item_and_update_google_sheet()
    {
        $this->sheetServiceMock->shouldReceive('updateSheet')->once();

        $item = Item::factory()->create();

        $this->itemService->delete($item);

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_generate_creates_items_and_updates_google_sheet()
    {
        $this->sheetServiceMock->shouldReceive('updateSheet')->once();

        $this->itemService->generate(10);

        $this->assertDatabaseCount('items', 10);
        $this->assertEquals(5, Item::where('status', 'Allowed')->count());
        $this->assertEquals(5, Item::where('status', 'Prohibited')->count());
    }

    public function test_clear_removes_all_items_and_updates_google_sheet()
    {
        $this->sheetServiceMock->shouldReceive('updateSheet')->once();

        Item::factory()->count(5)->create();

        $this->itemService->clear();

        $this->assertDatabaseCount('items', 0);
    }

    public function test_update_google_sheet_logs_error_on_failure()
    {
        $this->sheetServiceMock
            ->shouldReceive('updateSheet')
            ->andThrow(new \Exception('Test Error'));

        Item::factory()->create();

        $this->itemService->clear();

        Log::shouldHaveReceived('error')->once();
    }
}
