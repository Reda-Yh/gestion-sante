<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $message
    ) {}

    public function via($notifiable)
    {
        return ['database']; // plus tard : mail
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => $this->message,
            'date' => $this->appointment->appointment_at,
            'status' => $this->appointment->status,
        ];
    }
}
