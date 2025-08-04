<?php


namespace Maitiigor\RC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Maitiigor\RC\Models\ComplianceReport;

class ComplianceReportDeletedNotification extends Notification
{

    use Queueable;


    public $complianceReport;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ComplianceReport $complianceReport)
    {
        $this->complianceReport = $complianceReport;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('ComplianceReport deleted successfully')
                                ->markdown(
                                    'mail.complianceReports.deleted',
                                    ['complianceReport' => $this->complianceReport]
                                );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }

}
