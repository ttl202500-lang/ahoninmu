/**
 * Ahoninmu Japanese Learning - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Handle mission start button
        $(document).on('click', '.ahoninmu-btn[data-action="start"]', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var missionId = button.data('mission-id');
            var missionItem = button.closest('.ahoninmu-mission-item');
            
            button.prop('disabled', true).text('Đang xử lý...');
            
            $.ajax({
                url: ahoninmu_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'ahoninmu_start_mission',
                    nonce: ahoninmu_ajax.nonce,
                    daily_mission_id: missionId
                },
                success: function(response) {
                    if (response.success) {
                        // Change button to complete button
                        button.removeClass('btn-start').addClass('btn-complete');
                        button.attr('data-action', 'complete');
                        button.text('Hoàn thành');
                        button.prop('disabled', false);
                        
                        // Show notification
                        showNotification('success', response.data.message);
                    } else {
                        showNotification('error', response.data.message);
                        button.prop('disabled', false).text('Bắt đầu');
                    }
                },
                error: function() {
                    showNotification('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
                    button.prop('disabled', false).text('Bắt đầu');
                }
            });
        });
        
        // Handle mission complete button
        $(document).on('click', '.ahoninmu-btn[data-action="complete"]', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var missionId = button.data('mission-id');
            var missionItem = button.closest('.ahoninmu-mission-item');
            
            button.prop('disabled', true).text('Đang xử lý...');
            
            $.ajax({
                url: ahoninmu_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'ahoninmu_complete_mission',
                    nonce: ahoninmu_ajax.nonce,
                    daily_mission_id: missionId
                },
                success: function(response) {
                    if (response.success) {
                        // Mark mission as completed
                        missionItem.addClass('completed');
                        
                        // Replace button with checkmark
                        button.parent().html('<span class="completed-checkmark">✓</span>');
                        
                        // Add completion status to title
                        var title = missionItem.find('.mission-title');
                        if (!title.find('.mission-status').length) {
                            title.append('<span class="mission-status completed">✓ Đã hoàn thành</span>');
                        }
                        
                        // Update progress bar if exists
                        updateProgressBar(response.data.completed, response.data.total);
                        
                        // Show notification
                        showNotification('success', response.data.message);
                        
                        // Celebration effect
                        celebrateCompletion(missionItem);
                    } else {
                        showNotification('error', response.data.message);
                        button.prop('disabled', false).text('Hoàn thành');
                    }
                },
                error: function() {
                    showNotification('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
                    button.prop('disabled', false).text('Hoàn thành');
                }
            });
        });
        
        // Update progress bar
        function updateProgressBar(completed, total) {
            var percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            var progressBar = $('.ahoninmu-progress-notification .progress-bar');
            var progressText = $('.ahoninmu-progress-notification .progress-message strong');
            var progressPercentage = $('.ahoninmu-progress-notification .progress-percentage');
            
            if (progressBar.length) {
                progressBar.css('width', percentage + '%');
                progressPercentage.text(percentage + '%');
                progressText.text(completed + '/' + total);
                
                // Show congratulations if all completed
                if (percentage === 100) {
                    var congrats = $('.ahoninmu-progress-notification .progress-congrats');
                    if (!congrats.length) {
                        $('.ahoninmu-progress-notification .progress-text').append(
                            '<p class="progress-congrats">Chúc mừng! Bạn đã hoàn thành tất cả nhiệm vụ hôm nay! 🎉</p>'
                        );
                    }
                    
                    // Change icon
                    $('.ahoninmu-progress-notification .progress-icon').html('<span class="icon-trophy">🏆</span>');
                }
            }
        }
        
        // Show notification
        function showNotification(type, message) {
            var notification = $('<div class="ahoninmu-notification ' + type + '">' + message + '</div>');
            $('body').append(notification);
            
            setTimeout(function() {
                notification.addClass('show');
            }, 10);
            
            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 3000);
        }
        
        // Celebration effect
        function celebrateCompletion(element) {
            element.addClass('celebrate');
            setTimeout(function() {
                element.removeClass('celebrate');
            }, 1000);
        }
    });
    
})(jQuery);

// Add notification styles dynamically
(function() {
    var style = document.createElement('style');
    style.textContent = `
        .ahoninmu-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: 350px;
        }
        
        .ahoninmu-notification.show {
            transform: translateX(0);
        }
        
        .ahoninmu-notification.success {
            border-left: 4px solid #4caf50;
            color: #2e7d32;
        }
        
        .ahoninmu-notification.error {
            border-left: 4px solid #f44336;
            color: #c62828;
        }
        
        .ahoninmu-mission-item.celebrate {
            animation: celebrateAnimation 0.6s ease;
        }
        
        @keyframes celebrateAnimation {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }
    `;
    document.head.appendChild(style);
})();
