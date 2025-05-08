<?php

namespace App\Livewire\Login;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterationForm extends Component
{
    
    public $name, $email, $password, $password_confirmation;
    public $isSubmitting = false;
    public $successMessage = '';

    // Define the validation rules for registration
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:userstable,email',
        'password' => 'required|string|min:8|confirmed',
    ];

    // Call this function before submission
    public function beforeSubmit()
    {
        $this->isSubmitting = true;
        $this->successMessage = '';  // Clear success message if any.
        session()->forget('message');
    }

    // Handle user registration
    public function register()
    {
        $this->beforeSubmit();  // Trigger the pre-submit function

        $this->validate();  // Validate the form data

        try {
            // Use Query Builder to insert the user data into the database
            $userId = DB::table('userstable')->insertGetId([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'created_at' => now(),
                'updated_at' => now(),
                
            ]);
                        
            //do the companysettingstable
            DB::table('companysettingstable')->insert(
                [ 'companyID' => $userId, 
                  'companyName' => $this->name, 
                  'randomNo' => $this->generateUniqueRandomString()
                ]
            );
            
            //do for userstable
            DB::table('userstable') 
                ->where('id', $userId) 
                ->update(['companyID' => $userId]);


            // Trigger the post-submit function
            $this->afterSubmit();

            session()->flash('message', 'Registration successful!');

        } catch (\Exception $e) {
            $this->isSubmitting = false; // Reset submitting state
            throw ValidationException::withMessages([
                'email' => 'There was an error with your registration. Please try again.',
            ]);
        }
    }

    // Call this function after successful registration
    public function afterSubmit()
    {
        $this->successMessage = 'Registration successful!';
        $this->reset(); // Reset form fields
        $this->isSubmitting = false;
    }
    
    private function generateUniqueRandomString($length = 8)
    {
        do {
            // Generate a random alphanumeric string
            $randomString = Str::random($length);
        } while (DB::table('companysettingstable')->where('randomNo', $randomString)->exists());
    
        return $randomString;
    }
    
    public function render()
    {
        return view('livewire.login.registeration-form');
    }
}
