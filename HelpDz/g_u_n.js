(function() {
    function initNotifications() {
        let notificationCount = 0;

        function loadNotifications() {
            function requestNotifications(url, callback) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            console.error("Error loading notifications:", data.error);
                            callback(false);
                            return;
                        }
                        
                        if (data.expiredDeleted || data.expiredCleaned) {
                            console.log(`Expired notifications deleted: ${data.expiredDeleted}, cleaned up: ${data.expiredCleaned}`);
                        }
                        
                        if (data.notifications && Array.isArray(data.notifications)) {
                            notificationCount = data.unreadCount || 0;
                            $('#notificationCount').text(notificationCount);
                            
                            let notificationHtml = '';
                            data.notifications.forEach(function(notification) {
                                notificationHtml += `
                                    <div class="notification-item ${notification.is_read ? '' : 'unread'}">
                                        <p>${notification.subject}</p>
                                        <p>${notification.message}</p>
                                        <small>${notification.created_at}</small>
                                    </div>
                                `;
                            });
                            
                            $('#notificationDropdown').html(notificationHtml);
                        } else {
                            $('#notificationDropdown').html('<p>No notifications available.</p>');
                        }
                        
                        callback(true);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", status, error);
                        callback(false);
                    }
                });
            }

            requestNotifications('g_n.php', function(success) {
                if (!success) {
                    requestNotifications('HelpDz/g_n.php', function(success) {
                        if (!success) {
                            $('#notificationDropdown').html('<p>Error loading notifications from both locations.</p>');
                        }
                    });
                }
            });
        }

        $('#notificationIcon').click(function(e) {
            e.preventDefault();
            $('#notificationDropdown').toggle();
            if (notificationCount > 0) {
                function markNotificationsAsRead(url, callback) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                notificationCount = 0;
                                $('#notificationCount').text('0');
                                $('.notification-item').removeClass('unread');
                                callback(true);
                            } else if (data.error) {
                                console.error("Error marking notifications as read:", data.error);
                                callback(false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", status, error);
                            callback(false);
                        }
                    });
                }

                markNotificationsAsRead('m_n_r.php', function(success) {
                    if (!success) {
                        markNotificationsAsRead('HelpDz/m_n_r.php', function(success) {
                            if (!success) {
                                console.error("Failed to mark notifications as read from both locations.");
                            }
                        });
                    }
                });
            }
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('.notification-icon').length) {
                $('#notificationDropdown').hide();
            }
        });

        loadNotifications();
        setInterval(loadNotifications, 60000);
    }

    if (typeof jQuery === 'undefined') {
        var checkReady = function(callback) {
            if (window.jQuery) {
                callback(jQuery);
            } else {
                window.setTimeout(function() { checkReady(callback); }, 100);
            }
        };

        checkReady(function($) {
            $(document).ready(initNotifications);
        });
    } else {
        jQuery(document).ready(initNotifications);
    }
})();