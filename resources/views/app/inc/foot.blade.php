 <!-- Bootstrap 5 JS Bundle (Includes Popper.js) -->
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
 {{-- }
   <script src="{{asset('custom/app/js/fnon.min.js')}}" ></script>
 --}}
 
 <!-- Snap- Dialog -->
 <script src="https://cdn.jsdelivr.net/npm/snap-dialog-js@latest/dist/snap-dialog.js"></script>
<!-- Simple Nofification -->
<script src="https://cdn.jsdelivr.net/npm/simple-notify/dist/simple-notify.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/glide.min.js" integrity="sha512-IkLiryZhI6G4pnA3bBZzYCT9Ewk87U4DGEOz+TnRD3MrKqaUitt+ssHgn2X/sxoM7FxCP/ROUp6wcxjH/GcI5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/store2/2.14.4/store2.min.js" integrity="sha512-5VbMi8bmM1D+ipK8vQsBGvfV/2TIu/ZQUdh97op83OQCeWOsS37hAhA/xFLfR6e6fjAv/yc1HKS/afGFTQt3HA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 {{--  PWA JS files --}}
 <script src="{{ asset('/sw.js') }}"></script>
 <script>
     if ("serviceWorker" in navigator) {
         // Register a service worker hosted at the root of the
         // site using the default scope.
         navigator.serviceWorker.register("/sw.js").then(
         (registration) => {
             console.log("Service worker registration succeeded:", registration);
         },
         (error) => {
             console.error(`Service worker registration failed: ${error}`);
         },
         );
     } else {
         console.error("Service workers are not supported.");
     }
</script>

 
 {{--  End PWA JS files --}} 

 
 <!-- Optional: Custom JS 
 <script src="script.js"></script>
 -->