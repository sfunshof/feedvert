<div class="d-flex flex-column vh-100">
    <!-- Top Section (90%) -->
    <div class="flex-grow-9 d-flex">
        <!-- Left Section (20%) -->
        <div class="w-25 p-3" style="height: 100vh;">
            <!-- Scrollable Menu table container -->
            <div class="kiosk-height-gap"></div> 
            @php
                $logo=Auth::user()->CompanySettings->logo;
            @endphp
            <div class="text-center">
                <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $logo) }}" 
                    class="img-fluid" alt="Card Image" style="width:80px" >
            </div>
            <div class="height-5"></div>
            <h2 class="mb-3 text-center">Menu</h2>
            <div id="menuTableContainer" class="table-container menu-group">
                <table id="menuTable" style="border-collapse: collapse; width: 100%;">
                    <tbody>
                        @foreach($menuResults as $menuResult)
                            @php
                                $decoded = json_decode($menuResult->json, true);
                                $key = array_key_first($decoded);
                                $values = $decoded[$key];
                                // $this->js('console.log(' . $ . ')');
                            @endphp
                            <tr style="padding:0;margin:0;" wire:key="{{ $menuResult->id }}" wire:click="get_mealsFromMenu('{{ $key }}', {{ json_encode($values) }}, '{{$menuResult->name}}', {{$menuResult->mealTypeID}} , 1)">
                                <td style="padding:0;margin:0;">
                                    <div class="card">
                                        <div class="row g-0">
                                            <!-- Image on the left side -->
                                            <div class="col-md-4">
                                                <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/' . $menuResult->logo) }}" class="img-fluid" alt="Card Image">
                                            </div>
                                            <!-- Text on the right side -->
                                            <div class="col-md-8 d-flex align-items-center">
                                                <div class="card-body menu-item {{ $activeMenu === $menuResult->name ? 'active' : '' }}">
                                                    <p class="card-text menu-item ms-2">{{$menuResult->name}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End of Menu -->
        </div>

        <!-- Right Section (80%) -->
        <div class="w-75 bg-light p-3" x-data="{ itemBackToSubMenu: $wire.entangle('itemBackToSubMenu') }">
           @include('livewire.kiosk.fc.inc.menu_itemDisplay')
        </div>
    </div>

    <!-- Footer Section (10%) -->
    <footer class=" text-center p-3 flex-grow-1">
        <div class="container">
          <!-- Top Row -->
          <div class="row align-items-center mb-3">
              <!-- Left: Image with overlay -->
              <div class="col-4 position-relative mt-2">
                  <img src="{{ asset('custom/app/img/admin/paper-bag.png')}}"   style="width:64px;height:64px" class="img-fluid" alt="UCart">
                  <span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-pwarning">
                      {{$totalOrder}}
                  </span>
              </div>

              <!-- Center: Price -->
              <div class="col-4 text-center">
                  <span class="fs-5">  {{Auth::user()->companySettings->currency}}
                      @if ($totalCost > 0) 
                          {{ $totalCost}}
                      @else 
                          0.00
                      @endif
                  </span>
              </div>

              <!-- Right: Button -->
              <div class="col-4 text-end">
                  <button  @if($totalOrder == 0) disabled @endif   @click="$wire.showViewOrder()" class="btn btn-warning  kiosk-button">View My Order </button>
              </div>
          </div>

          <!-- Bottom Row  Cancel Order -->
          <div class="row">
              <div class="col-12"
                  x-data="{ simulateClick() {
                      const firstRow = document.querySelector('table#menuTable tr:first-child');
                      if (firstRow) {
                          firstRow.click();
                          $wire.mainMenuInit();
                      }
                      const table = document.getElementById('menuTableContainer');    
                      if (table){
                          table.scrollTop = 0;
                      }
                  }}">
                  <button  @if($totalOrder == 0) disabled @endif class="btn btn-outline-secondary  kiosk-button"  @click="simulateClick();">Cancel My  Order</button>
              </div>
          </div>
      </div>
    </footer>
</div>
