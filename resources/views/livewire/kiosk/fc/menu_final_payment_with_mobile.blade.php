<div class="container mt-5 pt-5 mb-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Instruction Text -->
            <div class="mt-5 pt-5 mb-5 pb-5">
                <h3> Please submit your payment Reference No.
                    Otherwise your order will not be processed
                </h3>
            </div>
            
            <div class="card shadow">
                <div class="card-body" x-data="{ inputText: $wire.entangle('inputText'), errorMessage: '' }">
                    <!-- Input Display Field -->
                    <div class="input-group mb-0">
                        <input type="text" 
                            class="form-control form-control-lg" 
                            placeholder="Payment Reference" 
                            x-model="inputText" 
                            readonly
                        >
                        @if(strlen($inputText) > 0)
                            <button class="btn btn-outline-secondary" type="button" wire:click="deleteCharacter">
                                <i class="bi bi-backspace-fill"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Validation Message -->
                    <template x-if="errorMessage">
                        <p class="text-danger mt-0 pt-0" x-text="errorMessage"></p>
                    </template>

                    <!-- Virtual Keyboard -->
                    <div class="virtual-keyboard mt-4">
                        @if($showAlphaKeyboard)
                            <!-- Alphabetic Keyboard -->
                            <div class="kb-wrapper">
                                <div class="kb-row">
                                    @foreach(['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'] as $key)
                                        <button class="kb-key" wire:click="addCharacter('{{ $key }}')">{{ $key }}</button>
                                    @endforeach
                                </div>
                                <div class="kb-row">
                                    <div class="kb-spacer"></div>
                                    @foreach(['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'] as $key)
                                        <button class="kb-key" wire:click="addCharacter('{{ $key }}')">{{ $key }}</button>
                                    @endforeach
                                    <div class="kb-spacer"></div>
                                </div>
                                <div class="kb-row">
                                    <button class="kb-key kb-special" wire:click="toggleKeyboard">123</button>
                                    @foreach(['Z', 'X', 'C', 'V', 'B', 'N', 'M'] as $key)
                                        <button class="kb-key" wire:click="addCharacter('{{ $key }}')">{{ $key }}</button>
                                    @endforeach
                                    <button class="kb-key kb-special kb-delete" wire:click="deleteCharacter">
                                        <i class="bi bi-backspace-fill"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Numeric Keyboard -->
                            <div class="kb-wrapper kb-numeric">
                                <div class="kb-row">
                                    @foreach(['1', '2', '3'] as $key)
                                        <button class="kb-key" wire:click="addCharacter('{{ $key }}')">{{ $key }}</button>
                                    @endforeach
                                </div>
                                <div class="kb-row">
                                    @foreach(['4', '5', '6'] as $key)
                                        <button class="kb-key" wire:click="addCharacter('{{ $key }}')">{{ $key }}</button>
                                    @endforeach
                                </div>
                                <div class="kb-row">
                                    @foreach(['7', '8', '9'] as $key)
                                        <button class="kb-key" wire:click="addCharacter('{{ $key }}')">{{ $key }}</button>
                                    @endforeach
                                </div>
                                <div class="kb-row">
                                    <button class="kb-key kb-special" wire:click="toggleKeyboard">ABC</button>
                                    <button class="kb-key" wire:click="addCharacter('0')">0</button>
                                    <button class="kb-key kb-special kb-delete" wire:click="deleteCharacter">
                                        <i class="bi bi-backspace-fill"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button 
                            class="btn btn-warning kiosk-button"
                            x-on:click="
                                if (inputText.trim() === '') {
                                    errorMessage = 'Please enter the payment reference';
                                } else {
                                    errorMessage = '';
                                    $wire.submitReference();
                                }
                            "
                        >
                            Submit Reference
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-light text-center text-dark py-3 fixed-bottom">
    <button class="btn btn-outline-secondary w-50 kiosk-button" 
       @click="$wire.back_fromPaymentMobilePage()">Back</button>
</footer>
