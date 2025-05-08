
    <script>
        let logoutFunction=function() {

            SnapDialog().warning('Do you really want to logout', 'Are you sure?', {
                enableConfirm: true,
                confirmText: 'Logout',
                onConfirm: function() {
                    @this.logout()
                },
                enableCancel: true,
                onCancel: function() {
                
                }
            });
        }
        
    </script>
