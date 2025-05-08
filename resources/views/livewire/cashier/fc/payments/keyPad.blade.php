<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div 
                        x-data="{
                            display: $wire.entangle('display'),
                            hasDecimal: false,
                            
                            handleKeyPress(key) {
                                if (key === 'c') {
                                    this.display = '';
                                    this.hasDecimal = false;
                                    return;
                                }
                                
                                if (key === '<-') {
                                    this.display = this.display.slice(0, -1);
                                    if (!this.display.includes('.')) {
                                        this.hasDecimal = false;
                                    }
                                    return;
                                }
                                
                                if (key === '.') {
                                    if (!this.hasDecimal) {
                                        this.display += '.';
                                        this.hasDecimal = true;
                                    }
                                    return;
                                }
                                
                                this.display += key;
                                $wire.checkBalance();
                            }
                        }"
                    >
                        <!-- Display -
                        <div class="mb-3">
                            <label for="displayText" class="form-label">Tendered</label>
                            <input  x-model="display"  readonly  type="text" class="form-control mb-3 w-50" id="displayText">
                        </div>
                        -->
                        <div class="ms-0 ps-0 mb-0">
                            <div class="row">
                              <div class="col-4 mt-2">
                                <button 
                                    @click="handleKeyPress('{{ $blankKeys[3]['value'] }}')" 
                                    class=" btn btn-outline-success  w-100 full-height fs-4 fw-bold">
                                    {{ $blankKeys[3]['value'] }} 
                                </button>
                              </div>
                              <div class="col-8">
                                <div class="row">
                                  <div class="col-12 mb-1">
                                    <label for="displayText" class="fs-5 fw-bold ">{{ $paymentLabel }}</label>
                                  </div>
                                  <div class="col-12">
                                        <input  x-model="display"  readonly  type="text" class="form-control mb-3 w-75" id="displayText">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                      

                        <!-- Keypad Grid -->
                        <div class="keypad-grid">
                            <!-- Row 1 -->
                            <button @click="handleKeyPress('1')" class="btn btn-outline-secondary  keypad-btn">1</button>
                            <button @click="handleKeyPress('2')" class="btn btn-outline-secondary keypad-btn">2</button>
                            <button @click="handleKeyPress('3')" class="btn btn-outline-secondary  keypad-btn">3</button>
                            <button 
                                @click="handleKeyPress('{{ $blankKeys[0]['value'] }}')" 
                                class="btn btn-outline-success  keypad-btn"
                            >{{ $blankKeys[0]['value'] }}</button>
                            
                            <!-- Row 2 -->
                            <button @click="handleKeyPress('4')" class="btn btn-outline-secondary  keypad-btn">4</button>
                            <button @click="handleKeyPress('5')" class="btn btn-outline-secondary  keypad-btn">5</button>
                            <button @click="handleKeyPress('6')" class="btn btn-outline-secondary  keypad-btn">6</button>
                            <button 
                                @click="handleKeyPress('{{ $blankKeys[1]['value'] }}')" 
                                class="btn btn-outline-success keypad-btn"
                            >{{ $blankKeys[1]['value'] }}</button>
                            
                            <!-- Row 3 -->
                            <button @click="handleKeyPress('7')" class="btn btn-outline-secondary  keypad-btn">7</button>
                            <button @click="handleKeyPress('8')" class="btn btn-outline-secondary  keypad-btn">8</button>
                            <button @click="handleKeyPress('9')" class="btn btn-outline-secondary  keypad-btn">9</button>
                            <button 
                                @click="handleKeyPress('{{ $blankKeys[2]['value'] }}')" 
                                class="btn btn-outline-success keypad-btn"
                            >{{ $blankKeys[2]['value'] }}</button>
                            
                            <!-- Row 4 -->
                            <button @click="handleKeyPress('c')" class="btn btn-outline-danger keypad-btn">C</button>
                            <button @click="handleKeyPress('0')" class="btn btn-outline-secondary  keypad-btn">0</button>
                            <button @click="handleKeyPress('.')" class="btn btn-outline-secondary  keypad-btn">&#46;</button>
                            <button @click="handleKeyPress('<-')" class="btn btn-outline-warning  keypad-btn">&#x232b;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        Livewire.on('balanceExceeded', amount => {
            // Handle balance exceeded
            //console.log(`Amount ${amount} exceeds balance`);
            alert("ok")
        });
    </script>
 @endscript      
