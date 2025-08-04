<?php


namespace Maitiigor\RC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Maitiigor\RC\Models\CompanyUser;

class CompanyUserDeletedNotification extends Notification
{

    use Queueable;


    public $companyUser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CompanyUser $companyUser)
    {
        $this->companyUser = $companyUser;
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
        return (new MailMessage)->subject('CompanyUser deleted successfully')
                                ->markdown(
                                    'mail.companyUsers.deleted',
                                    ['companyUser' => $this->companyUser]
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
