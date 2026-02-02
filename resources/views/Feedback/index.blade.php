@extends('layouts.admin')

@section('title', 'Feedback Management')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Feedback Management</h1>
        <p class="text-gray-600 mt-2">Kelola semua feedback dari user</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Total Feedback</p>
            <p class="text-2xl font-bold text-gray-800">{{ $feedbacks->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Belum Dibaca</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $feedbacks->where('is_read_by_admin', false)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Sudah Dibalas</p>
            <p class="text-2xl font-bold text-green-600">{{ $feedbacks->where('status', 'replied')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-red-600">{{ $feedbacks->where('status', 'pending')->count() }}</p>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Daftar Feedback</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($feedbacks as $feedback)
                            <tr class="{{ !$feedback->is_read_by_admin ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $feedback->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($feedback->user->name) }}" 
                                             class="w-8 h-8 rounded-full mr-2">
                                        <span class="font-medium">{{ $feedback->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $feedback->subject ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ Str::limit($feedback->message, 50) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $feedback->status === 'replied' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($feedback->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $feedback->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="viewFeedback({{ $feedback->id }})" 
                                            class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button onclick="deleteFeedback({{ $feedback->id }})" 
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada feedback
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal View & Reply -->
<div id="feedbackModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-gray-800">Detail Feedback</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="feedbackContent"></div>
        </div>
    </div>
</div>

<script>
let currentFeedbackId = null;

async function viewFeedback(id) {
    currentFeedbackId = id;
    const feedback = @json($feedbacks).find(f => f.id === id);
    
    const content = `
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600">Dari:</p>
                <p class="font-semibold">${feedback.user.name}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600">Subject:</p>
                <p class="font-semibold">${feedback.subject || '-'}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600">Pesan:</p>
                <p class="text-gray-800 bg-gray-50 p-3 rounded">${feedback.message}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600">Tanggal:</p>
                <p>${new Date(feedback.created_at).toLocaleString('id-ID')}</p>
            </div>
            
            ${feedback.admin_reply ? `
                <div class="bg-blue-50 border-l-4 border-blue-500 p-3">
                    <p class="text-sm font-semibold text-blue-800 mb-1">Balasan Anda:</p>
                    <p class="text-sm">${feedback.admin_reply}</p>
                </div>
            ` : ''}
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Balas Feedback:</label>
                <textarea id="replyMessage" rows="4" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Tulis balasan Anda...">${feedback.admin_reply || ''}</textarea>
            </div>
            
            <div class="flex gap-3">
                <button onclick="sendReply()" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Kirim Balasan
                </button>
                <button onclick="closeModal()" 
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Tutup
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('feedbackContent').innerHTML = content;
    document.getElementById('feedbackModal').classList.remove('hidden');
    
    // Mark as read
    await fetch(`/admin/feedback/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}

async function sendReply() {
    const reply = document.getElementById('replyMessage').value;
    
    if(!reply.trim()) {
        alert('Balasan tidak boleh kosong');
        return;
    }
    
    try {
        const response = await fetch(`/admin/feedback/${currentFeedbackId}/reply`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reply: reply })
        });
        
        const data = await response.json();
        
        if(data.success) {
            alert(data.message);
            window.location.reload();
        }
    } catch(error) {
        alert('Terjadi kesalahan');
    }
}

async function deleteFeedback(id) {
    if(!confirm('Yakin ingin menghapus feedback ini?')) return;
    
    try {
        const response = await fetch(`/admin/feedback/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if(data.success) {
            alert(data.message);
            window.location.reload();
        }
    } catch(error) {
        alert('Terjadi kesalahan');
    }
}

function closeModal() {
    document.getElementById('feedbackModal').classList.add('hidden');
}
</script>
@endsection