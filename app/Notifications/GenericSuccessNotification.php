<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericSuccessNotification extends Notification  implements ShouldQueue
{

    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($title = 'Process Success', $message = 'Its all good.')
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    
    public function toMail($notifiable)
    {
        // Build an email that includes a link to download the PDF
        return (new MailMessage)
                    ->subject('Your PDF report is ready')
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('Your requested PDF report has been generated.')
                    ->action('Download PDF', 'asasasa') //route('reports.download', '348957')) //$this->report->id))
                    ->line('Thank you for using our application!');
    }

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'message' => 'Your PDF report is ready!',
    //         'report_id' => '1', //'$this->report->id',
    //         'path' => '$this->report->path', 
    //     ];
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your PDF report is ready!',
            'report_id' => '1',//'$this->report->id',
            'path' => '$this->report->path', 
        ];
    }
}
