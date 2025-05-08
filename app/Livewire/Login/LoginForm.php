<?php

namespace App\Livewire\Login;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Livewire\WithFileUploads;

class LoginForm extends Component
{
    public $email, $password;
    public $isSubmitting = false;
    public $errorMessage = '';
    public $successMessage = '';
    
   
    // Define validation rules
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ];
    
    // Function to handle login
    public function login() 
      {
          
          $this->isSubmitting = true;
          $this->errorMessage = '';  // Reset any previous error message
          $this->successMessage = '';  // Reset any previous success message
          session()->forget('message');
          session()->forget('error');

          $this->validate(); // Validate the form data
  
          // Attempt to log the user in using Fortify's built-in authentication
          if (Auth::attempt(['email' => $this->email, 'password' => $this->password], true)) {
              // Call a post-login function after successful login
              $this->afterLogin();
          } else {
              // Handle failed login attempt
              $this->isSubmitting = false;
              $this->errorMessage = 'These credentials do not match our records.';
              session()->flash('error', $this->errorMessage);
          }
      }
  
      // Function to handle after successful login
      public function afterLogin()
      {
          $this->successMessage = 'You have successfully logged in!';
          $this->isSubmitting = false;
          session()->flash('message', $this->successMessage);
          $this->reset();  // Reset the form fields
          
          // Set success message that Alpine.js can watch for
          $this->successMessage = 'You have successfully logged in!';
             
          // Optionally, you can redirect or trigger other actions after login here
      }
  
    
    public function render()
    {
        return view('livewire.login.login-form');
    }
}
