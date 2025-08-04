<?php

namespace Maitiigor\RC\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Maitiigor\RC\Models\InvoiceItem;

class InvoiceItemUpdatedNotification extends Notification
{

    use Queueable;


    public $invoiceItem;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InvoiceItem $invoiceItem)
    {
        $this->invoiceItem = $invoiceItem;
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
        return (new MailMessage)->subject('InvoiceItem updated successfully')
                                ->markdown(
                                    'mail.invoiceItems.updated',
                                    ['invoiceItem' => $this->invoiceItem]
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
