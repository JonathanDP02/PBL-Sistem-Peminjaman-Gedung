use Tests\TestCase;
use App\Models\User;

class RoomTest extends TestCase
{
    public function test_room_cannot_be_created_without_unit_id()
    {
        $user = User::factory()->create([
            'unit_id' => null
        ]);

        $this->actingAs($user);

        $response = $this->post('/rooms', [
            'building_id' => 1,
            'room_name' => 'Test Room',
            'capacity' => 10
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['unit_id']);
    }
}