<?php

namespace App\Livewire\Login;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotForm extends Component
{
    public $email;
    public $isSubmitting = false;
    public $successMessage = '';
    public $errorMessage = '';

    // Validation rules for the email
    protected $rules = [
        'email' => 'required|email|exists:userstable,email',
    ];

    // Handle the form submission
    public function submit()
    {
        $this->isSubmitting = true;
        $this->successMessage = '';
        $this->errorMessage = '';
        session()->forget('message');
        session()->forget('error');

        $this->validate();  // Validate the form data

        // Send password reset link
        $response = Password::sendResetLink(['email' => $this->email]);

        // Check if the reset link was sent successfully
        if ($response == Password::RESET_LINK_SENT) {
            $this->successMessage = 'We have emailed your password reset link!';
            session()->flash('message', $this->successMessage);
            $this->reset();  // Reset form fields after success
        } else {
            $this->errorMessage = 'There was an issue sending the reset link. Please try again.';
            session()->flash('error', $this->errorMessage);
        }

        $this->isSubmitting = false;
    }
    
    public function render()
    {
        return view('livewire.login.forgot-form');
    }
}
