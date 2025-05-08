<div>
    
</div>
@script
    <script>
         $wire.on('start-countdown', () => {
            if ($wire.isRunning) return; // Prevent multiple starts
            $wire.set('isRunning', true);
            $wire.set('countdown', 10); // Reset countdown

            const interval = setInterval(() => {
                if ($wire.countdown > 0) {
                    $wire.countdown--; // Decrement countdown
                    $wire.set('countdown',  $wire.countdown);
                } else {
                    clearInterval(interval); // Stop the interval
                    $wire.set('isRunning', false);
                    $wire.set('countdown', 10); 
                    //@this.call('countdownComplete'); // Call Livewire function
                    document.getElementById('initBtnId').click();

                }
            }, 1000); // Update every second


        });


    </script>
@endscript
