<?php

namespace App\Mail;

use App\Models\Pet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class VaccineReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param Pet        $pet                The pet with overdue/upcoming vaccines
     * @param Collection $overdueVaccinations Vaccinations that are past due_date
     * @param Collection $upcomingVaccinations Vaccinations due within the next 7 days
     */
    public function __construct(
        public Pet        $pet,
        public Collection $overdueVaccinations,
        public Collection $upcomingVaccinations,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $petName = $this->pet->name;

        return new Envelope(
            subject: "💉 Recordatorio de Vacunas — {$petName} necesita su refuerzo",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vaccine_reminder',
        );
    }
}
