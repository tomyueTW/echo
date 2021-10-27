<?php

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

Route::get('/tasks', function () {
    return \App\Models\Task::latest()->pluck('body');
});

Route::post('/tasks', function () {
    $task = \App\Models\Task::forceCreate(request(['body']));

    event(
        (new \App\Events\TaskCreated($task))->dontBroadcastToCurrentUser()
    );
});

Route::get('/update/{id}', function ($id) {
    \App\Events\OrderStatusUpdate::dispatch(new Order($id));
});
