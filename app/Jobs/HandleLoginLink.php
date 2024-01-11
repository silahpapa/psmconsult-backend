<?php

namespace App\Jobs;

use App\Mail\RegistrationMail;
use App\Mail\LoginLinkEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class HandleLoginLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct( protected $user, protected $link)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email =  $this->user->email;
        Mail::to($email)->send(new LoginLinkEmail($this->link));
    }
}
