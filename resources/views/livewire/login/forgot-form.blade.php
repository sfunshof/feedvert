<div class="container">
    {{-- Be like water. --}}
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mt-5">Forgot Password</h2> 
            @if (session()->has('message')) 
                <div class="alert alert-success"> 
                    {{ session('message') }} 
                </div> 
            @endif 
            @if (session()->has('error')) 
                <div class="alert alert-danger"> 
                    {{ session('error') }} 
                </div> 
            @endif
            <form wire:submit.prevent="submit">
               <div class="mb-3"> 
                   <label for="username" class="form-label">Email</label>
                    <input type="text"  wire:model="email"   class="form-control" id="username" placeholder="Enter your username">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div> 
                <button type="submit" class="btn btn-primary w-100"  wire:loading.attr="disabled" wire:target="login"  >
                    @if ($isSubmitting) Sending reset link... @else Send Reset Link @endif
               </button> 
           </form> 
           <div class="mt-3 text-center">
            <a href="#" @click.prevent="$wire.$parent.showLoginForm()" >Back to Login</a>
        </div>
    </div>

</div>
