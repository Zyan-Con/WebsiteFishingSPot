{{-- resources/views/admin/feedback/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'User Feedback')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User Feedback</h1>
            <p class="text-gray-600 mt-1">Kelola dan balas feedback dari pengguna</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-medium">
                <i class="fas fa-envelope"></i> 
                {{ $unreadCount }} Belum Dibaca
            </span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="flex border-b">
            <button class="filter-tab active px-6 py-3 font-medium text-gray-700 border-b-2 border-blue-600" data-filter="all">
                <i class="fas fa-inbox"></i> Semua ({{ $totalCount }})
            </button>
            <button class="filter-tab px-6 py-3 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-filter="unread">
                <i class="fas fa-envelope"></i> Belum Dibaca ({{ $unreadCount }})
            </button>
            <button class="filter-tab px-6 py-3 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-filter="replied">
                <i class="fas fa-check-circle"></i> Sudah Dibalas ({{ $repliedCount }})
            </button>
            <button class="filter-tab px-6 py-3 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-filter="pending">
                <i class="fas fa-clock"></i> Menunggu ({{ $pendingCount }})
            </button>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="bg-white rounded-lg shadow-sm">
        @forelse($feedbacks as $feedback)
        <div class="feedback-item border-b last:border-b-0 p-6 hover:bg-gray-50 transition {{ !$feedback->is_read_by_admin ? 'bg-blue-50' : '' }}" 
             data-status="{{ $feedback->admin_reply ? 'replied' : 'pending' }}"
             data-read="{{ $feedback->is_read_by_admin ? 'read' : 'unread' }}">
            
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($feedback->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            {{ $feedback->user->name }}
                            @if(!$feedback->is_read_by_admin)
                                <span class="ml-2 px-2 py-1 text-xs bg-blue-600 text-white rounded-full">BARU</span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500">
                            <i class="far fa-clock"></i> {{ $feedback->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    @if($feedback->admin_reply)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle"></i> Dibalas
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                            <i class="fas fa-clock"></i> Menunggu
                        </span>
                    @endif
                    
                    <button class="text-red-600 hover:text-red-700 p-2" 
                            onclick="deleteFeedback({{ $feedback->id }})"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Subject -->
            @if($feedback->subject)
            <div class="mb-3">
                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-sm font-medium">
                    <i class="fas fa-tag"></i> {{ $feedback->subject }}
                </span>
            </div>
            @endif

            <!-- Message -->
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                <p class="text-gray-700 leading-relaxed">{{ $feedback->message }}</p>
            </div>

            <!-- Admin Reply -->
            @if($feedback->admin_reply)
            <div class="mb-4 p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-reply text-green-600"></i>
                    <span class="text-sm font-medium text-green-700">Balasan Anda</span>
                    <span class="text-xs text-gray-500">
                        • {{ $feedback->replied_at ? $feedback->replied_at->diffForHumans() : '' }}
                    </span>
                </div>
                <p class="text-gray-700 leading-relaxed">{{ $feedback->admin_reply }}</p>
            </div>
            @endif

            <!-- Reply Form -->
            <div class="reply-form-container" id="replyForm{{ $feedback->id }}" style="{{ $feedback->admin_reply ? 'display: none;' : '' }}">
                <form onsubmit="submitReply(event, {{ $feedback->id }})" class="mt-4">
                    @csrf
                    <textarea name="reply" 
                              rows="3" 
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Tulis balasan Anda..."></textarea>
                    <div class="flex gap-2 mt-3">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane"></i> Kirim Balasan
                        </button>
                        @if($feedback->admin_reply)
                        <button type="button" 
                                onclick="toggleReplyForm({{ $feedback->id }})"
                                class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 mt-4">
                @if(!$feedback->admin_reply)
                <button onclick="document.getElementById('replyForm{{ $feedback->id }}').style.display='block';" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    <i class="fas fa-reply"></i> Balas
                </button>
                @else
                <button onclick="toggleReplyForm({{ $feedback->id }})" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm">
                    <i class="fas fa-edit"></i> Edit Balasan
                </button>
                @endif
                
                @if(!$feedback->is_read_by_admin)
                <button onclick="markAsRead({{ $feedback->id }})" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm">
                    <i class="fas fa-check"></i> Tandai Dibaca
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-inbox text-6xl mb-4 opacity-30"></i>
            <p class="text-lg">Belum ada feedback dari pengguna</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $feedbacks->links() }}
    </div>
</div>

<script>
// Filter Tabs
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(t => {
            t.classList.remove('active', 'text-blue-600', 'border-blue-600');
            t.classList.add('text-gray-500', 'border-transparent');
        });
        this.classList.add('active', 'text-blue-600', 'border-blue-600');
        this.classList.remove('text-gray-500', 'border-transparent');
        
        const filter = this.dataset.filter;
        
        // Filter feedback items
        document.querySelectorAll('.feedback-item').forEach(item => {
            if (filter === 'all') {
                item.style.display = 'block';
            } else if (filter === 'unread') {
                item.style.display = item.dataset.read === 'unread' ? 'block' : 'none';
            } else if (filter === 'replied') {
                item.style.display = item.dataset.status === 'replied' ? 'block' : 'none';
            } else if (filter === 'pending') {
                item.style.display = item.dataset.status === 'pending' ? 'block' : 'none';
            }
        });
    });
});

// Toggle Reply Form
function toggleReplyForm(id) {
    const form = document.getElementById('replyForm' + id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Submit Reply
async function submitReply(event, feedbackId) {
    event.preventDefault();
    const form = event.target;
    const reply = form.reply.value;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    
    try {
        const response = await fetch(`/admin/feedback/${feedbackId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reply: reply })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ Balasan berhasil dikirim!');
            location.reload();
        } else {
            alert('❌ ' + (data.message || 'Gagal mengirim balasan'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Mark as Read
async function markAsRead(feedbackId) {
    try {
        const response = await fetch(`/admin/feedback/${feedbackId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Delete Feedback
async function deleteFeedback(feedbackId) {
    if (!confirm('Apakah Anda yakin ingin menghapus feedback ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/feedback/${feedbackId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ Feedback berhasil dihapus!');
            location.reload();
        } else {
            alert('❌ Gagal menghapus feedback');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan');
    }
}
</script>

<style>
.filter-tab {
    transition: all 0.2s;
}

.filter-tab:hover {
    background-color: #f9fafb;
}

.filter-tab.active {
    background-color: #eff6ff;
}
</style>
@endsection