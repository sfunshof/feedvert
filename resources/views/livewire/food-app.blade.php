    <div  x-data="{logoutFunction(){ logoutFunction()}}" 
          class="">   
        @include('app.inc.inc-food-app')                   
        
        
        @if ($login)
            <livewire:login.login-form />
        @endif

        @if ($registration)
            <livewire:login.registeration-form />
        @endif
        
        @if ($forgot)
            <livewire:login.forgot-form />
        @endif

        @if ($menu)
            @include('livewire.fakecomponents.menu')
        @endif
        
        @if ($kiosk)
            <div class="hide-scrollbar" style="height: 100vh;"> 
                <livewire:kiosk.kiosk    :menu-results="$menuResults"/>
            </div> 
        @endif

        @if ($kitchen)
            <livewire:kitchen.kitchen />
        @endif
        
        @if ($cashier)
            <livewire:cashier.cashier />
        @endif
        
        @if ($orderDisplay)
            <livewire:order-display.order-display />
        @endif
        
        @if ($CDU)
            <div class="hide-scrollbar" style="height: 100vh;"> 
               <livewire:cdu.cdu/>
             </div> 
        @endif

        @if ($admin)
            <livewire:admin.admin />
        @endif

        @if ($paymentRef)
             <livewire:pay-ref.pay-ref />
        @endif
    </div>   
</div>
