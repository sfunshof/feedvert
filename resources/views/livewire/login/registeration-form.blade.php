<div class="container">
    {{-- Success is as dangerous as failure --}}
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mt-5">Admin Registration</h2>

            @if (session()->has('message')) 
                <div class="alert alert-success"> 
                    {{ session('message') }} 
                </div> 
            @endif
    
            <form wire:submit.prevent="register">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Company Name</label>
                    <input type="text"  wire:model="name"  class="form-control" id="name" placeholder="Enter your company name">
                    @error('name') <span class="small text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Username</label>
                    <input type="email"  wire:model="email"  class="form-control" id="email" placeholder="Enter your email">
                    @error('email') <span class="small text-danger">{{ $message }}</span> @enderror
                </div>
                

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"  wire:model="password"   class="form-control" id="password" placeholder="Enter your password">
                    @error('password') <span class="small text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" wire:model="password_confirmation"   class="form-control" id="confirm-password" placeholder="Confirm your password">
                </div>
                
                <div class="mb-3 text-center" x-data>
                    <a href="#"  @click.prevent="$wire.$parent.showLoginForm()"  class="link-primary">Already Registered?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled" wire:target="register">
                    @if($isSubmitting) Registering... @else Register @endif
                </button>
            </form>
        </div>
    </div>
    
</div>
