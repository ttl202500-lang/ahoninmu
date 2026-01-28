/**
 * Ahoninmu Japanese Learning - Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Confirm before deleting mission
        $('.ahoninmu-admin').on('click', 'a[href*="delete_mission"]', function(e) {
            if (!confirm('Bạn có chắc muốn xóa nhiệm vụ này?')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Auto-dismiss notices
        setTimeout(function() {
            $('.notice.is-dismissible').fadeOut();
        }, 5000);
        
    });
    
})(jQuery);
