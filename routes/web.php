<?php

use App\Events\MessageCreated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

class Order
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}

Route::get('/', function () {
    return view('welcome');
});

// order
Route::get('/update/{id}', function ($id) {
    \App\Events\OrderStatusUpdate::dispatch(new Order($id));
});

//task
Route::get('/tasks', function () {
    return \App\Models\Task::latest()->pluck('body');
});

Route::post('/tasks', function () {
    $task = \App\Models\Task::forceCreate(request(['body']));
    event(
        (new \App\Events\TaskCreated($task))->dontBroadcastToCurrentUser()
    );
});

//room
Route::get('rooms/{room}', function(\App\Models\Room $room) {
    $room->load('messages');

    return view('rooms.show', compact('room'));
});

Route::get('api/rooms/{room}', function(\App\Models\Room $room) {
    return $room->messages->pluck('body');
});

Route::post('api/rooms/{room}/messages', function(\App\Models\Room $room) {
    $message = $room->messages()->create(request(['body']));

    event(new MessageCreated($message));

    return $message;
});
