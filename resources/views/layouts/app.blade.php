<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
       <div id="app">
        <div class="container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-anchor"></i>
                    </div>
                    <div class="logo-text">{{ config('app.name', 'FishingApp') }}</div>
                    <span class="badge-new">Baru</span>
                </div>

                <!-- CTA Banner -->
                <a href="{{ route('premium.index') }}" class="cta-banner-link">
                    <div class="cta-banner animated-banner">
                        <div class="cta-icon">🌟</div>
                        <div class="cta-title">Mulai uji coba gratis</div>
                        <div class="cta-subtitle">7 hari</div>
                    </div>
                </a>

                <!-- Menu -->
                <div class="menu-section">
                    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home menu-icon"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                    <a href="{{ route('locations.index') }}" class="menu-item {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                        <i class="fas fa-location-dot menu-icon"></i>
                        <span class="menu-text">Lokasi</span>
                        <span class="menu-count {{ request()->routeIs('locations.*') ? 'active' : '' }}" id="locationCount">0</span>
                    </a>
                    <a href="{{ route('catches.index') }}" class="menu-item {{ request()->routeIs('catches.*') ? 'active' : '' }}">
                        <i class="fas fa-fish menu-icon"></i>
                        <span class="menu-text">Tangkapan</span>
                        <span class="menu-count {{ request()->routeIs('catches.*') ? 'active' : '' }}">0</span>
                    </a>
                </div>

                <!-- Prakiraan Menu -->
                <div class="menu-section">
                    <div class="menu-label">Prakiraan</div>
                    
                    <a href="{{ route('forecast.activity') }}" class="menu-item {{ request()->routeIs('forecast.activity') ? 'active' : '' }}">
                        <i class="fas fa-fish menu-icon"></i>
                        <span class="menu-text">Aktivitas Ikan</span>
                    </a>
                    
                    <a href="{{ route('forecast.tide') }}" class="menu-item {{ request()->routeIs('forecast.tide') ? 'active' : '' }}">
                        <i class="fas fa-water menu-icon"></i>
                        <span class="menu-text">Pasang Surut</span>
                    </a>
                    
                    <a href="{{ route('forecast.wave') }}" class="menu-item {{ request()->routeIs('forecast.wave') ? 'active' : '' }}">
                        <i class="fas fa-wave-square menu-icon"></i>
                        <span class="menu-text">Gelombang</span>
                    </a>
                    
                    <a href="{{ route('forecast.weather') }}" class="menu-item {{ request()->routeIs('forecast.weather') ? 'active' : '' }}">
                        <i class="fas fa-cloud-sun menu-icon"></i>
                        <span class="menu-text">Cuaca</span>
                    </a>
                </div>

                <!-- User Profile -->
                 <div class="user-profile profile-dropdown">
                <div class="user-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="user-status">● Online</div>
                </div>
                <i class="fas fa-ellipsis-vertical" style="color: #9ca3af; cursor: pointer;" onclick="toggleDropdown()"></i>
                
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="#" class="dropdown-item" onclick="openProfileModal(event, 'profile')">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="#" class="dropdown-item" onclick="openProfileModal(event, 'edit')">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            @include('profile.modal')
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Bar -->
                <div class="top-bar">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" placeholder="Cari lokasi, tangkapan, atau prakiraan..." autocomplete="off">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    <div class="top-bar-actions">
                        <!-- ✅ FEEDBACK BUTTON - BARU! -->
                        <button class="icon-button" id="feedbackButton" title="Feedback & Support">
                            <i class="fas fa-comment-dots"></i>
                            <span class="notification-badge" id="feedbackBadge" style="display: none;">0</span>
                        </button>

                        <!-- Notification Button -->
                        <button class="icon-button" id="notificationButton" title="Notifikasi">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationBadge">0</span>
                        </button>

                        <!-- Settings Button -->
                        <a href="{{ route('settings') }}" class="icon-button" title="Pengaturan">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div> 
                </div>

                <!-- ✅ FEEDBACK POPUP - BARU! -->
                <div class="notification-popup" id="feedbackPopup" style="display: none;">
                    <div class="notification-header">
                        <h3>
                            <i class="fas fa-comment-dots"></i>
                            Feedback & Support
                        </h3>
                        <div class="notification-actions">
                            <button class="btn-close" id="closeFeedbackBtn" title="Tutup">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div style="display: flex; border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                        <!-- <button class="feedback-tab active" data-tab="new" style="flex: 1; padding: 12px; border: none; background: none; cursor: pointer; font-weight: 500; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s;">
                            <i class="fas fa-plus-circle"></i> Kirim Baru
                        </button> -->
                        <button class="feedback-tab" data-tab="history" style="flex: 1; padding: 12px; border: none; background: none; cursor: pointer; font-weight: 500; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s;">
                            <i class="fas fa-history"></i> Riwayat
                        </button>
                    </div>

                    <div class="notification-body" id="feedbackBody">
                        <!-- Tab: Kirim Baru -->
                        <div class="feedback-tab-content active" data-content="new">
                            <form id="feedbackForm" style="padding: 20px;">
                                @csrf
                                <div style="margin-bottom: 16px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #374151;">
                                        Subjek (opsional)
                                    </label>
                                    <input type="text" name="subject" class="chat-input" placeholder="Judul feedback..." style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                                </div>
                                <div style="margin-bottom: 16px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #374151;">
                                        Pesan <span style="color: #ef4444;">*</span>
                                    </label>
                                    <textarea name="message" rows="5" placeholder="Tulis pesan Anda..." required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                                </div>
                                <button type="submit" style="width: 100%; padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: background 0.2s;">
                                    <i class="fas fa-paper-plane"></i> Kirim Feedback
                                </button>
                            </form>
                        </div> 

                        <!-- Tab: Riwayat -->
                        <div class="feedback-tab-content" data-content="history" style="display: none;">
                            <div id="feedbackHistory" style="padding: 20px; max-height: 400px; overflow-y: auto;">
                                <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
                                    <p style="margin-top: 12px;">Memuat riwayat...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Popup (yang sudah ada) -->
                <div class="notification-popup" id="notificationPopup">
                    <div class="notification-header">
                        <h3>
                            <i class="fas fa-bell"></i>
                            Notifikasi
                        </h3>
                        <div class="notification-actions">
                            <button class="btn-text" id="markAllReadBtn" title="Tandai semua sudah dibaca">
                                <i class="fas fa-check-double"></i> Tandai Semua
                            </button>
                            <button class="btn-close" id="closeNotificationBtn" title="Tutup">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="notification-body" id="notificationBody">
                        <div class="notification-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p style="margin-top: 12px;">Memuat notifikasi...</p>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @stack('scripts')

    <script>

// ==================================================================================
// NOTIFICATION SYSTEM
// ==================================================================================
jQuery(document).ready(function($) {
    const notificationButton = $('#notificationButton');
    const notificationPopup = $('#notificationPopup');
    const notificationBody = $('#notificationBody');
    const notificationBadge = $('#notificationBadge');
    const closeNotificationBtn = $('#closeNotificationBtn');
    const markAllReadBtn = $('#markAllReadBtn');

    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toggle Notification Popup
    notificationButton.on('click', function(e) {
        e.stopPropagation();
        
        // Close feedback popup if open
        $('#feedbackPopup').removeClass('show');
        
        notificationPopup.toggleClass('show');
        
        if (notificationPopup.hasClass('show')) {
            loadNotifications();
        }
    });

    // Close Notification Popup
    closeNotificationBtn.on('click', function() {
        notificationPopup.removeClass('show');
    });

    // Close popup when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.notification-popup, #notificationButton, #feedbackButton').length) {
            notificationPopup.removeClass('show');
            
            // Close feedback popup
            const feedbackPopup = document.getElementById('feedbackPopup');
            if (feedbackPopup) {
                feedbackPopup.style.display = 'none';
                feedbackPopup.classList.remove('show');
            }
        }
    });

    // Load Notifications
    function loadNotifications() {
        notificationBody.html('<div class="notification-loading"><i class="fas fa-spinner fa-spin"></i><p style="margin-top: 12px;">Memuat notifikasi...</p></div>');

        $.ajax({
            url: '/notifications',
            method: 'GET',
            success: function(response) {
                updateNotificationBadge(response.unread_count);
                displayNotifications(response.notifications);
            },
            error: function(xhr) {
                console.error('Error loading notifications:', xhr);
                notificationBody.html('<div class="notification-empty"><i class="fas fa-exclamation-triangle"></i><p>Gagal memuat notifikasi</p></div>');
            }
        });
    }

    // Display Notifications
    function displayNotifications(notifications) {
        if (notifications.length === 0) {
            notificationBody.html('<div class="notification-empty"><i class="fas fa-bell-slash"></i><p>Tidak ada notifikasi</p></div>');
            return;
        }

        let html = '';
        notifications.forEach(function(notif) {
            const timeAgo = formatTimeAgo(notif.created_at);
            const unreadClass = !notif.is_read ? 'unread' : '';
            const icon = notif.icon || 'fa-bell';

            html += '<div class="notification-item ' + unreadClass + '" data-id="' + notif.id + '" data-link="' + (notif.link || '#') + '">';
            html += '<div class="notification-content">';
            html += '<div class="notification-icon ' + notif.type + '"><i class="fas ' + icon + '"></i></div>';
            html += '<div class="notification-text">';
            html += '<div class="notification-title">' + notif.title + '</div>';
            html += '<div class="notification-message">' + notif.message + '</div>';
            html += '<div class="notification-time"><i class="far fa-clock"></i>' + timeAgo + '</div>';
            html += '</div></div>';
            html += '<button class="notification-delete" data-id="' + notif.id + '"><i class="fas fa-trash"></i></button>';
            html += '</div>';
        });

        notificationBody.html(html);
    }

    // Update Notification Badge
    function updateNotificationBadge(count) {
        if (count > 0) {
            notificationBadge.text(count > 99 ? '99+' : count).addClass('show');
        } else {
            notificationBadge.removeClass('show');
        }
    }

    // Format Time Ago
    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'Baru saja';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';
        if (seconds < 2592000) return Math.floor(seconds / 604800) + ' minggu lalu';
        
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'short', 
            year: 'numeric' 
        });
    }

    // Mark as Read when clicking notification
    notificationBody.on('click', '.notification-item', function(e) {
        if ($(e.target).closest('.notification-delete').length) return;

        const notifId = $(this).data('id');
        const link = $(this).data('link');
        
        if ($(this).hasClass('unread')) {
            markAsRead(notifId);
        }

        if (link && link !== '#') {
            window.location.href = link;
        }
    });

    // Mark as Read
    function markAsRead(notifId) {
        $.ajax({
            url: '/notifications/' + notifId + '/read',
            method: 'POST',
            success: function() {
                loadNotifications();
            }
        });
    }

    // Mark All as Read
    markAllReadBtn.on('click', function() {
        $.ajax({
            url: '/notifications/read-all',
            method: 'POST',
            success: function() {
                loadNotifications();
            }
        });
    });

    // Delete Notification
    notificationBody.on('click', '.notification-delete', function(e) {
        e.stopPropagation();
        const notifId = $(this).data('id');

        if (confirm('Hapus notifikasi ini?')) {
            $.ajax({
                url: '/notifications/' + notifId,
                method: 'DELETE',
                success: function() {
                    loadNotifications();
                }
            });
        }
    });

    // Auto refresh notifications every 30 seconds
    setInterval(function() {
        if (!notificationPopup.hasClass('show')) {
            $.ajax({
                url: '/notifications',
                method: 'GET',
                success: function(response) {
                    updateNotificationBadge(response.unread_count);
                }
            });
        }
    }, 30000);

    // Load initial badge count on page load
    $.ajax({
        url: '/notifications',
        method: 'GET',
        success: function(response) {
            updateNotificationBadge(response.unread_count);
        },
        error: function(xhr) {
            console.error('Error loading initial notification count:', xhr);
        }
    });
});

// ==================================================================================
// FEEDBACK SYSTEM
// ==================================================================================
document.addEventListener('DOMContentLoaded', function() {
    const feedbackButton = document.getElementById('feedbackButton');
    const feedbackPopup = document.getElementById('feedbackPopup');
    const feedbackBadge = document.getElementById('feedbackBadge');
    const closeFeedbackBtn = document.getElementById('closeFeedbackBtn');
    const feedbackTabs = document.querySelectorAll('.feedback-tab');
    const feedbackForm = document.getElementById('feedbackForm');

    if (!feedbackButton) return;

    // Toggle Feedback Popup
    feedbackButton.addEventListener('click', function(e) {
        e.stopPropagation();
        
        const notificationPopup = document.getElementById('notificationPopup');
        if (notificationPopup) {
            notificationPopup.classList.remove('show');
        }
        
        if (feedbackPopup.style.display === 'none' || feedbackPopup.style.display === '') {
            feedbackPopup.style.display = 'block';
            feedbackPopup.classList.add('show');
            
            const activeTab = document.querySelector('.feedback-tab.active');
            if (activeTab && activeTab.dataset.tab === 'history') {
                loadFeedbackHistory();
            }
            checkUnreadCount();
        } else {
            feedbackPopup.style.display = 'none';
            feedbackPopup.classList.remove('show');
        }
    });

    // Close Feedback Popup
    closeFeedbackBtn.addEventListener('click', function() {
        feedbackPopup.style.display = 'none';
        feedbackPopup.classList.remove('show');
    });

    // Switch Tabs
    feedbackTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            feedbackTabs.forEach(t => {
                t.classList.remove('active');
                t.style.color = '#6b7280';
                t.style.borderBottomColor = 'transparent';
            });
            this.classList.add('active');
            this.style.color = '#3b82f6';
            this.style.borderBottomColor = '#3b82f6';
            
            document.querySelectorAll('.feedback-tab-content').forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });
            
            const targetContent = document.querySelector('[data-content="' + targetTab + '"]');
            if (targetContent) {
                targetContent.style.display = 'block';
                targetContent.classList.add('active');
            }

            if (targetTab === 'history') {
                loadFeedbackHistory();
            }
        });
    });

    // Submit Feedback Form
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('/feedback', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    feedbackForm.reset();
                    alert('✅ Feedback berhasil dikirim!');
                    
                    const historyTab = document.querySelector('[data-tab="history"]');
                    if (historyTab) {
                        historyTab.click();
                    }
                    
                    checkUnreadCount();
                } else {
                    alert('❌ ' + (data.message || 'Gagal mengirim feedback'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    // Load Feedback History
    async function loadFeedbackHistory() {
        const historyContainer = document.getElementById('feedbackHistory');
        if (!historyContainer) return;

        historyContainer.innerHTML = '<div style="text-align: center; padding: 40px 20px; color: #9ca3af;"><i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i><p style="margin-top: 12px;">Memuat riwayat...</p></div>';

        try {
            const response = await fetch('/feedback', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const result = await response.json();
                const feedbacks = result.feedbacks || result;
                
                if (!feedbacks || feedbacks.length === 0) {
                    historyContainer.innerHTML = '<div style="text-align: center; padding: 40px 20px; color: #9ca3af;"><i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i><p>Belum ada riwayat feedback</p></div>';
                } else {
                    historyContainer.innerHTML = feedbacks.map(function(feedback) {
                        const createdDate = new Date(feedback.created_at);
                        const formattedDate = createdDate.toLocaleString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        const isUnread = !feedback.is_read_by_user && feedback.admin_reply;
                        const bgColor = isUnread ? 'background: #eff6ff;' : '';

                        let html = '<div style="padding: 16px; border-bottom: 1px solid #e5e7eb; ' + bgColor + '">';
                        
                        if (feedback.subject) {
                            html += '<div style="font-weight: 600; color: #1f2937; margin-bottom: 8px;">' + feedback.subject + '</div>';
                        }
                        
                        html += '<div style="color: #6b7280; font-size: 14px; margin-bottom: 8px; line-height: 1.5;">' + feedback.message + '</div>';
                        
                        if (feedback.admin_reply) {
                            html += '<div style="margin-top: 12px; padding: 12px; background: #f0fdf4; border-left: 3px solid #10b981; border-radius: 4px;">';
                            html += '<div style="font-size: 12px; color: #059669; font-weight: 500; margin-bottom: 6px;"><i class="fas fa-reply"></i> Balasan Admin</div>';
                            html += '<p style="font-size: 13px; color: #047857; margin: 0; line-height: 1.5;">' + feedback.admin_reply + '</p>';
                            if (feedback.replied_at) {
                                const repliedDate = new Date(feedback.replied_at).toLocaleString('id-ID');
                                html += '<div style="font-size: 11px; color: #10b981; margin-top: 6px;"><i class="far fa-clock"></i> ' + repliedDate + '</div>';
                            }
                            html += '</div>';
                        } else {
                            html += '<span style="display: inline-block; font-size: 12px; color: #f59e0b; background: #fef3c7; padding: 4px 8px; border-radius: 4px; margin-top: 8px;"><i class="fas fa-clock"></i> Menunggu balasan</span>';
                        }
                        
                        html += '<div style="margin-top: 8px; font-size: 12px; color: #9ca3af;"><i class="far fa-calendar"></i> ' + formattedDate + '</div>';
                        html += '</div>';
                        
                        return html;
                    }).join('');

                    feedbacks.forEach(function(feedback) {
                        if (!feedback.is_read_by_user && feedback.admin_reply) {
                            fetch('/feedback/' + feedback.id + '/read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json'
                                }
                            });
                        }
                    });
                }
            } else {
                throw new Error('Response is not JSON');
            }
        } catch (error) {
            console.error('Error loading feedback history:', error);
            historyContainer.innerHTML = '<div style="text-align: center; padding: 40px 20px; color: #ef4444;"><i class="fas fa-exclamation-circle" style="font-size: 48px; margin-bottom: 16px;"></i><p>Gagal memuat riwayat feedback</p><button onclick="location.reload()" style="margin-top: 12px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">Coba Lagi</button></div>';
        }
    }

    // Check Unread Count
    async function checkUnreadCount() {
        try {
            const response = await fetch('/feedback/unread-count');
            const data = await response.json();
            
            if (data.count > 0) {
                feedbackBadge.textContent = data.count > 99 ? '99+' : data.count;
                feedbackBadge.style.display = 'flex';
            } else {
                feedbackBadge.style.display = 'none';
            }
        } catch (error) {
            console.error('Error checking unread count:', error);
        }
    }

    setInterval(checkUnreadCount, 30000);
    checkUnreadCount();
});
// ==================================================================================
// SEARCH FUNCTIONALITY
// ==================================================================================
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        if (query.length < 2) {
            searchResults.classList.remove('show');
            return;
        }
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    });
}

function performSearch(query) {
    console.log('Searching for:', query);
    // Implement your search logic here
}

// Close search on outside click
document.addEventListener('click', function(e) {
    if (searchInput && searchResults) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('show');
        }
    }
});
    </script>
    @stack('scripts')   
</body>
</html> 