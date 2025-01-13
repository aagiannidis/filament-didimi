<?php

use App\Livewire;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Mail\Visualbuilder\EmailTemplates\UserLogin;
use App\Jobs\GenerateFuelOrderJob;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    
    $toEmail = 'a.agiannidis@gmail.com';
    $data = [
        'subject' => 'Welcome Email',
        'body' => 'This is a test email sent from the Laravel application.',
    ];
    
    try {
        // Mail::raw($data['body'], function ($message) use ($toEmail, $data) {
        //     $message->to($toEmail)
        //             ->subject($data['subject']);
        // });
    
        // // Send the email
        //Mail::to($toEmail)->send(new UserLogin(user::first()));
    
        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Email sent successfully.',
            'recipient' => $toEmail,
        ], 200);
    } catch (\Exception $e) {
        // Return a JSON response indicating failure
        return response()->json([
            'message' => 'Failed to send email.',
            'error' => $e->getMessage(),
        ], 500);
    }

    return view('welcome');
});

Route::get('login', Livewire\UserLogin::class)->name('login');

// Route::group([
//     'middleware' => 'auth',
//     'prefix' => 'tickets',
//     'as' => 'tickets.',
// ], function () {
//     Route::get('/', Livewire\ListTickets::class)->name('index');
//     Route::get('create', Livewire\CreateTicket::class)->name('create');
//     Route::get('{ticket}/edit', Livewire\EditTicket::class)->name('edit');
//     Route::get('{ticket}', Livewire\ViewTicket::class)->name('view');
// });


Route::get('/pdf', function () {
   
    //return view('templates.pdf.RefuelingOrder');

    // try {
    //     $data = [
    //         'title' => 'Monthly Sales',
    //         'items' => [
    //             ['product' => 'Laptop',  'quantity' => 10],
    //             ['product' => 'Monitor', 'quantity' => 5],
    //         ],
    //     ];
    //     return view('templates.pdf.RefuelingOrder', compact('data'));
    //     //return  Pdf::loadView('templates.pdf.RefuelingOrder', compact('data'))->setPaper('a4', 'landscape')->download('invoice.pdf');
    //     //return $pdf->download('invoice.pdf');
        
    // } catch (\Exception $e) {
    //     // Return a JSON response indicating failure
    //     return response()->json([
    //         'message' => 'Failed to generate pdf.',
    //         'error' => $e->getMessage(),
    //     ], 500);
    // }

    $data = [
        'user_id' => 1,
        'report_id' => 21,
        'include_charts' => true,        
        'title' => 'Monthly Sales',
        'items' => [
            ['product' => 'Laptop',  'quantity' => 10],
            ['product' => 'Monitor', 'quantity' => 5],
        ],
    ];
    

    //GenerateFuelOrderJob::dispatch($data);
    dispatch(new GenerateFuelOrderJob($data));

    return response()->json(['message' => 'Job dispatched']);

    
});

Route::get('login', Livewire\UserLogin::class)->name('login');

// Route::group([
//     'middleware' => 'auth',
//     'prefix' => 'tickets',
//     'as' => 'tickets.',
// ], function () {
//     Route::get('/', Livewire\ListTickets::class)->name('index');
//     Route::get('create', Livewire\CreateTicket::class)->name('create');
//     Route::get('{ticket}/edit', Livewire\EditTicket::class)->name('edit');
//     Route::get('{ticket}', Livewire\ViewTicket::class)->name('view');
// });
