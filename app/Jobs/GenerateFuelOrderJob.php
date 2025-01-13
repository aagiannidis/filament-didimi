<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Notifications\GenericSuccessNotification;
use App\Notifications\GenericFailureNotification;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;

class GenerateFuelOrderJob implements ShouldQueue
{    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {        
        $data = $this->data;
        $pdf = Pdf::loadView('templates.pdf.RefuelingOrder', compact('data'))->setPaper('a4', 'landscape');
        // 3. Save the PDF
        $fileName = 'report_' . $this->data['report_id'] . '.pdf';
        $pdfPath  = storage_path('app/reports/' . $fileName);
        $pdf->save($pdfPath);

        // 4. Update the report record in the database
        // $this->report->update([
        //     'status' => 'completed',
        //     'path'   => 'reports/' . $fileName,
        // ]);

        // 5. Notify the user that the report is ready
        //    The "user" relation is defined in the Report model
        //$this->report->user->notify(new \App\Notifications\GenericSuccessNotification());//;$this->report));
        User::find($this->data['user_id'])->notify(new \App\Notifications\GenericSuccessNotification());//;$this->report));
    }

    public function failed(\Exception $exception)
    {
        //$this->report->user->notify(new \App\Notifications\GenericFailureNotification($exception->getMessage()));
        User::find($this->data['user_id'])->notify(new \App\Notifications\GenericFailureNotification($exception->getMessage()));//;$this->report));
    }
}
