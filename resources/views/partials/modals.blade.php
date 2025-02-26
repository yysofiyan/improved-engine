<!-- Modal Logout -->
<div class="fixed inset-0 z-50 hidden" id="logoutModal" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold" id="modalTitle">Siap untuk Keluar?</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                    <span class="sr-only">Tutup</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 text-gray-600">
                Pilih "Keluar" di bawah jika Anda siap mengakhiri sesi saat ini.
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end p-4 border-t space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
                    Batal
                </button>
                <a href="{{ route('logout') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Keluar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>