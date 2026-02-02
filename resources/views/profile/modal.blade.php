<style>
    .profile-dropdown {
        position: relative;
    }
.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 200px;
    display: none;
    z-index: 1000;
    margin-top: 8px;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    padding: 12px 16px;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #374151;
    text-decoration: none;
}

.dropdown-item:hover {
    background: #f3f4f6;
}

.dropdown-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 8px 0;
}

.profile-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 2000;
    align-items: center;
    justify-content: center;
}

.profile-modal.show {
    display: flex;
}

.profile-modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
}

.profile-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.profile-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
}

.profile-body {
    padding: 20px;
}

.profile-banner {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    position: relative;
    overflow: hidden;
    margin-bottom: 60px;
}

.profile-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-avatar-section {
    position: absolute;
    bottom: -50px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
}

.profile-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.change-photo-btn, .change-banner-btn {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.change-photo-btn:hover, .change-banner-btn:hover {
    background: #4338ca;
}

.change-banner-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0,0,0,0.6);
}

.change-banner-btn:hover {
    background: rgba(0,0,0,0.8);
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.stats-section {
    display: flex;
    gap: 20px;
    margin: 20px 0;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
}

.stat-item {
    flex: 1;
    text-align: center;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #4f46e5;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.save-profile-btn {
    width: 100%;
    padding: 12px;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    margin-top: 20px;
}

.save-profile-btn:hover {
    background: #4338ca;
}

.tabs {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 16px;
}

.tab {
    padding: 12px 20px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.2s;
}

.tab.active {
    color: #4f46e5;
    border-bottom-color: #4f46e5;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

#photoInput, #bannerInput {
    display: none;
}

.crop-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 3000;
    align-items: center;
    justify-content: center;
}

.crop-modal.show {
    display: flex;
}

.crop-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    max-width: 90%;
    max-height: 90vh;
    overflow: auto;
}

.crop-area {
    max-width: 100%;
    max-height: 60vh;
    margin: 20px 0;
}

.crop-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.crop-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.crop-btn-cancel {
    background: #e5e7eb;
    color: #374151;
}

.crop-btn-save {
    background: #4f46e5;
    color: white;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    color: #f59e0b;
    margin: 12px 0;
    justify-content: center;
}

.rating-stars {
    display: flex;
    gap: 2px;
}
</style>
<!-- Profile Modal -->
<div class="profile-modal" id="profileModal">
    <div class="profile-modal-content">
        <div class="profile-header">
            <h2 id="modalTitle">My Profile</h2>
            <button class="close-modal" onclick="closeProfileModal()">&times;</button>
        </div>
    <div class="profile-body">
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="profile" onclick="switchTab('profile')">Profile</div>
            <div class="tab" data-tab="edit" onclick="switchTab('edit')">Edit Profile</div>
        </div>

        <!-- Tab: Profile View -->
        <div class="tab-content active" id="profileTab">
            <div class="profile-banner" id="profileBannerView">
                <div class="profile-avatar-section">
                    <div class="profile-avatar-large" id="profileAvatar">
                        <span id="profileAvatarText">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</span>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 16px;">
                <h3 id="profileName" style="font-size: 24px; font-weight: 700; margin-bottom: 4px;">{{ Auth::user()->name ?? 'User' }}</h3>
                <p style="color: #6b7280; margin-bottom: 8px;" id="profileEmail">{{ Auth::user()->email }}</p>
                
                <div class="rating-display">
                    <div class="rating-stars" id="profileRatingStars"></div>
                    <span id="profileRating">0.0</span>
                    <span style="color: #9ca3af;">(<span id="profileTotalReviews">0</span> reviews)</span>
                </div>
            </div>

            <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number" id="postsCount">0</div>
                    <div class="stat-label">Posts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="ratingCount">0.0</div>
                    <div class="stat-label">Rating</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="reviewsCount">0</div>
                    <div class="stat-label">Reviews</div>
                </div>
            </div>

            <div class="form-group">
                <label>Bio</label>
                <p style="color: #6b7280;" id="profileBio">No bio yet</p>
            </div>
        </div>

        <!-- Tab: Edit Profile -->
        <div class="tab-content" id="editTab">
            <form id="profileForm" onsubmit="saveProfile(event)">
                @csrf
                
                <div class="profile-banner" id="bannerPreview">
                    <button type="button" class="change-banner-btn" onclick="document.getElementById('bannerInput').click()">
                        <i class="fas fa-camera"></i> Change Banner
                    </button>
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-large" id="avatarPreview">
                            <span id="avatarPreviewText">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</span>
                        </div>
                    </div>
                </div>

                <input type="file" id="bannerInput" accept="image/*" onchange="openCropModal(event, 'banner')">
                <input type="file" id="photoInput" accept="image/*" onchange="openCropModal(event, 'avatar')">
                
                <div style="text-align: center; margin-top: 16px;">
                    <button type="button" class="change-photo-btn" onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-camera"></i> Change Avatar
                    </button>
                </div>

                <div class="form-group" style="margin-top: 24px;">
                    <label>Name</label>
                    <input type="text" id="nameInput" name="name" value="{{ Auth::user()->name ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="emailInput" name="email" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="form-group">
                    <label>Bio</label>
                    <textarea id="bioInput" name="bio" placeholder="Tell us about yourself..." maxlength="500">{{ Auth::user()->bio ?? '' }}</textarea>
                    <small style="color: #9ca3af;">Max 500 characters</small>
                </div>

                <button type="submit" class="save-profile-btn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
</div>
<!-- Crop Modal -->
<div class="crop-modal" id="cropModal">
    <div class="crop-container">
        <h3 style="margin-bottom: 16px;">Crop Image</h3>
        <div>
            <img id="cropImage" class="crop-area">
        </div>
        <div class="crop-buttons">
            <button class="crop-btn crop-btn-cancel" onclick="closeCropModal()">Cancel</button>
            <button class="crop-btn crop-btn-save" onclick="saveCroppedImage()">Save</button>
        </div>
    </div>
</div>
<!-- Cropper.js CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
    let cropper = null;
    let currentCropType = '';
    let croppedAvatarData = null;
    let croppedBannerData = null;

    function openCropModal(event, type) {
        const file = event.target.files[0];
        if (!file) return;

        currentCropType = type;
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('cropImage').src = e.target.result;
            document.getElementById('cropModal').classList.add('show');
            
            if (cropper) cropper.destroy();
            
            cropper = new Cropper(document.getElementById('cropImage'), {
                aspectRatio: type === 'avatar' ? 1 : 16/9,
                viewMode: 2,
                responsive: true,
                autoCropArea: 1,
            });
        };
        
        reader.readAsDataURL(file);
    }

    function saveCroppedImage() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: currentCropType === 'avatar' ? 400 : 1200,
            height: currentCropType === 'avatar' ? 400 : 675,
        });

        const base64 = canvas.toDataURL('image/png');

        if (currentCropType === 'avatar') {
            croppedAvatarData = base64;
            document.getElementById('avatarPreview').innerHTML = `<img src="${base64}">`;
        } else {
            croppedBannerData = base64;
            const avatarHtml = croppedAvatarData 
                ? `<img src="${croppedAvatarData}">`
                : `<span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</span>`;
                
            document.getElementById('bannerPreview').innerHTML = `
                <img src="${base64}">
                <button type="button" class="change-banner-btn" onclick="document.getElementById('bannerInput').click()">
                    <i class="fas fa-camera"></i> Change Banner
                </button>
                <div class="profile-avatar-section">
                    <div class="profile-avatar-large" id="avatarPreview">
                        ${avatarHtml}
                    </div>
                </div>
            `;
        }

        closeCropModal();
    }

    function closeCropModal() {
        document.getElementById('cropModal').classList.remove('show');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    async function loadProfileData() {
        try {
            const response = await fetch('/profile/data');
            const data = await response.json();
            
            document.getElementById('profileName').textContent = data.user.name;
            document.getElementById('profileEmail').textContent = data.user.email;
            document.getElementById('profileBio').textContent = data.user.bio || 'No bio yet';
            
            if (data.user.avatar) {
                document.getElementById('profileAvatar').innerHTML = `<img src="${data.user.avatar}">`;
                document.getElementById('avatarPreview').innerHTML = `<img src="${data.user.avatar}">`;
            }

            if (data.user.banner) {
                const avatarImg = data.user.avatar ? `<img src="${data.user.avatar}">` : `<span>${data.user.name.substring(0,2).toUpperCase()}</span>`;
                
                document.getElementById('profileBannerView').innerHTML = `
                    <img src="${data.user.banner}">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-large" id="profileAvatar">${avatarImg}</div>
                    </div>
                `;
                
                document.getElementById('bannerPreview').innerHTML = `
                    <img src="${data.user.banner}">
                    <button type="button" class="change-banner-btn" onclick="document.getElementById('bannerInput').click()">
                        <i class="fas fa-camera"></i> Change Banner
                    </button>
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-large" id="avatarPreview">${avatarImg}</div>
                    </div>
                `;
            }

            document.getElementById('postsCount').textContent = data.stats.posts;
            document.getElementById('ratingCount').textContent = data.stats.rating;
            document.getElementById('reviewsCount').textContent = data.stats.total_reviews;
            document.getElementById('profileRating').textContent = data.stats.rating;
            document.getElementById('profileTotalReviews').textContent = data.stats.total_reviews;
            
            renderStars(parseFloat(data.stats.rating));
            
        } catch (error) {
            console.error('Error loading profile:', error);
        }
    }

    function renderStars(rating) {
        const container = document.getElementById('profileRatingStars');
        let stars = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= Math.floor(rating)) {
                stars += '<i class="fas fa-star"></i>';
            } else if (i === Math.ceil(rating) && rating % 1 !== 0) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            } else {
                stars += '<i class="far fa-star"></i>';
            }
        }
        
        container.innerHTML = stars;
    }

    async function saveProfile(event) {
        event.preventDefault();
        
        console.log('=== SAVE PROFILE DEBUG ===');
        
        const payload = {
            name: document.getElementById('nameInput').value,
            email: document.getElementById('emailInput').value,
            bio: document.getElementById('bioInput').value,
            avatar: croppedAvatarData,
            banner: croppedBannerData,
        };
        
        console.log('Payload:', {
            name: payload.name,
            email: payload.email,
            bio: payload.bio,
            hasAvatar: !!payload.avatar,
            hasBanner: !!payload.banner,
        });
        
        try {
            const response = await fetch('/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });
            
            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                alert('✅ ' + data.message);
                
                const userAvatar = document.querySelector('.user-avatar');
                if (data.user.avatar) {
                    userAvatar.innerHTML = `<img src="${data.user.avatar}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                }
                
                document.querySelector('.user-name').textContent = data.user.name;
                
                loadProfileData();
                switchTab('profile');
                
                croppedAvatarData = null;
                croppedBannerData = null;
            } else {
                alert('❌ ' + (data.message || 'Failed to update profile'));
            }
            
        } catch (error) {
            console.error('ERROR:', error);
            alert('❌ Error: ' + error.message);
        }
    }

    function openProfileModal(event, tab = 'profile') {
        event.preventDefault();
        document.getElementById('profileModal').classList.add('show');
        loadProfileData();
        
        setTimeout(() => {
            document.querySelectorAll('.tabs .tab[data-tab]').forEach(t => t.classList.remove('active'));
            const targetTab = document.querySelector(`.tab[data-tab="${tab}"]`);
            if (targetTab) targetTab.classList.add('active');
            
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tab + 'Tab').classList.add('active');
            
            const titles = { profile: 'My Profile', edit: 'Edit Profile' };
            document.getElementById('modalTitle').textContent = titles[tab];
        }, 100);
    }

    function closeProfileModal() {
        document.getElementById('profileModal').classList.remove('show');
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tabs .tab[data-tab]').forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById(tabName + 'Tab').classList.add('active');
        
        const titles = { profile: 'My Profile', edit: 'Edit Profile' };
        document.getElementById('modalTitle').textContent = titles[tabName];
    }

    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.profile-dropdown')) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.remove('show');
        }
    });

    document.getElementById('profileModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeProfileModal();
        }
    });
</script>