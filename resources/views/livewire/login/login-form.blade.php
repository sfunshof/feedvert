<div class="container">
    {{-- The Master doesn't talk, he acts. --}}
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mt-5">Login</h2> 
            {{--  DO NOT SHOW SUCCESS LOGIN 
            @if (session()->has('message')) 
                <div class="alert alert-success"> 
                    {{ session('message') }} 
                </div> 
            @endif 
            --}}
            @if (session()->has('error')) 
                <div class="alert alert-danger"> 
                    {{ session('error') }} 
                </div> 
            @endif

            <div x-data="{ showButton: false, successMessage: @entangle('successMessage') }" x-init="
            $watch('successMessage', (newValue) => {
                if (newValue) {
                    // Simulate the hidden button click after successful login
                    showButton = false; // This makes the button visible
                    $nextTick(() => { $refs.hiddenButton.click(); }); // Simulate button click
                }
            })
        ">


            <form wire:submit.prevent="login">
               <div class="mb-3"> 
                   <label for="username" class="form-label">Email</label>
                    <input type="text"  wire:model="email"   class="form-control" id="username" placeholder="Enter your username">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div> 
                <div class="mb-3"> 
                   <label for="password" class="form-label">Password</label> 
                   <input type="password"  wire:model="password" class="form-control" id="password" placeholder="Enter your password">
                   @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div> 
                <div class="mb-3 d-flex justify-content-between" x-data> 
                   <a href="#"  @click.prevent="$wire.$parent.showForgotForm()" class="link-primary">Forgot Password?</a> 
                   <a href="#"  @click.prevent="$wire.$parent.showRegisterForm()"   class="link-primary">Register</a> 
                </div> 
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

               <button type="submit" class="btn btn-primary w-100"  wire:loading.attr="disabled" wire:target="login"  >
                    @if ($isSubmitting) Logging in... @else Login @endif
               </button> 

               {{-- Hidden Button that gets triggered via Alpine.js --}}
                <button x-ref="hiddenButton" style="display:none;"@click.prevent="$wire.$parent.showMenu()">
                    Hidden Button
                </button>
           </form> 
       </div> 
   </div>
</div>
