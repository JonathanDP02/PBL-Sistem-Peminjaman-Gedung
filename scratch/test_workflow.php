<?php

use App\Models\Booking;
use App\Models\Position;
use App\Models\Room;
use App\Models\User;
use App\Services\WorkflowBridgeService;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'like', '%wri%')->orWhere('email', 'like', '%user%')->first();
$room = Room::where('room_name', 'like', '%Graha%')->first();

$booking = new Booking([
    'user_id' => $user->id,
    'room_id' => $room->id,
    'event_scope' => 'Lintas Jurusan',
]);
$booking->setRelation('user', $user);
$booking->setRelation('room', $room);

$service = new WorkflowBridgeService;
$chain = $service->resolveStepChain($booking, 'Lintas Jurusan');

foreach ($chain as $idx => $step) {
    $pos = Position::find($step['position_id']);
    echo 'Step '.($idx + 1).': '.($pos ? $pos->name : 'Unknown').' (ID: '.$step['position_id'].', Tier: '.$step['tier_label'].")\n";
}
