<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\BillingDocument $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice generated: #' . ($this->invoice->document_number ?? 'INV-'.$this->invoice->id),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_generated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $this->invoice->load(['client', 'organization', 'items']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ops.invoice.pdf.template', ['invoice' => $this->invoice]);
        
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'Invoice_' . $this->invoice->document_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
