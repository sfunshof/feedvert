<div class="container ">
    <div class="row " x-data="{ activeTab: 'cashUp',
                               orderNo:$wire.entangle('orderNo'),
                               order_menu:$wire.entangle('order_menu')
                            }">
        <!-- Left Sidebar - 2 columns -->
        <div class="col-md-2 bg-light pe-0 px-0 mx-0">
            <nav class="sidebar-menu">
                <ul class="list-unstyled">
                    <li class="menu-item" :class="{ 'active': activeTab === 'cashUp' }">
                        <a href="#" @click.prevent="activeTab = 'cashUp'; $wire.getTotalSales();">
                            <i class="fas fa-cash-register me-2"></i>Cash Up
                        </a>
                    </li>
                    <li class="menu-item" :class="{ 'active': activeTab === 'ref' }">
                        <a href="#" @click.prevent="activeTab = 'ref'; $wire.verifyPayReference();">
                            <i class="fas fa-check-circle me-2"></i>Reference Verification
                        </a>
                    </li>
                    {{--  Suspended: This should be hold and will be implemented later
                        <button class="btn btn-outline-secondary" @click="activeTab = 'cancel'; order_menu='cancel'; $wire.init_generateOrderNo(); ">
                            Order Cancellation
                        </button>
                     --}}
                    <li class="menu-item" :class="{ 'active': activeTab === 'payment' }">
                        <a href="#" @click.prevent="activeTab = 'payment'; order_menu='pay'; $wire.init_generateOrderNo();">
                            <i class="fas fa-credit-card me-2"></i>Order Payment
                        </a>
                    </li>
                    <li class="menu-item" :class="{ 'active': activeTab === 'refund' }">
                        <a href="#" @click.prevent="activeTab = 'refund'; $wire.init_generateOrderNo();">
                            <i class="fas fa-undo-alt me-2"></i>Refund Payment
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Right Content Area - 10 columns -->
        <div class="col-md-10  ps-3 ">
            <!-- Show One -->
            <div x-show="activeTab === 'cashUp'" class="p-3 border rounded bg-light">
                 @include('livewire.cashier.inc.modal-functions-cashup')
            </div>

            <!-- Show Two -->
            <div x-show="activeTab === 'ref'" class="p-3 border rounded bg-light">
                @include('livewire.pay-ref.pay-ref')
            </div>
            <!-- Order -Payment -->
            <div x-show="activeTab === 'payment'" class="p-3 border rounded bg-light">
                 @include('livewire.cashier.inc.generate-order',
                 ['data' =>$menu_order, 'noOrderMsg' => 'This order cannot be paid for',
                 'explainMsg' => 'These are orders  requested  but payment not yet done'
                 ])
            </div>
            <!-- Cancel an order -->
            <div x-show="activeTab === 'cancel'" class="p-3 border rounded bg-light">
                @include('livewire.cashier.inc.generate-order',
                ['data' =>$menu_order, 'noOrderMsg' => 'This order cannot be cancelled',
                'explainMsg' => 'These are orders sent to the kitchen but need to be cancelled'
                ])
            </div>
            <!-- Show  Refund-->
            <div x-show="activeTab === 'refund'" class="p-3 border rounded bg-light">
                @include('livewire.cashier.fc.refund')
            </div>
        </div>
    </div>
</div>