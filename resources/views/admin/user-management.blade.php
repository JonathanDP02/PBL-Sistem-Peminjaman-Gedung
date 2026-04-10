<x-app-layout title="Manajemen User">
    <div class="relative px-8 pt-4 pb-8 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
            <div>
                <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Manajemen User</h2>
                <p class="text-sm text-slate-500 dark:text-gray-400 max-w-xl leading-relaxed">
                    Kelola data pengguna dengan fitur create, read, update, dan delete.
                </p>
            </div>
            <button onclick="openAddUserModal()" class="bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-[0_0_15px_rgba(20,184,166,0.3)] shrink-0">
                <i class="ph-bold ph-user-plus text-lg"></i> Tambah User
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Total Pengguna</p>
                <div class="flex items-end gap-2">
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">2,842</h2>
                    <span class="text-xs font-bold text-teal-600 dark:text-kinetic-primary mb-0.5">+12%</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Super Admin</p>
                <div class="flex items-end gap-2">
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">5</h2>
                    <span class="text-xs font-medium text-slate-500 dark:text-gray-400 mb-0.5">Global</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Unit Admin</p>
                <div class="flex items-end gap-2">
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">48</h2>
                    <span class="text-xs font-medium text-slate-500 dark:text-gray-400 mb-0.5">Tersebar</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Aktif Sekarang</p>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-kinetic-primary animate-pulse"></span>
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">156</h2>
                </div>
            </div>
        </div>

        <!-- Alert Message -->
        <div id="alertContainer" class="hidden"></div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden flex items-center justify-center p-8">
            <div class="animate-spin">
                <i class="ph-bold ph-spinner text-3xl text-kinetic-primary"></i>
            </div>
            <span class="ml-3 text-slate-600 dark:text-gray-300">Memuat data...</span>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl shadow-sm dark:shadow-none overflow-hidden transition-colors">
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-[#2A2A2A] bg-slate-50/50 dark:bg-[#111]">
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Nama</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Unit</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Level</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest"></th>Role</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody id="usersTableBody" class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                        <!-- Data akan dimuat oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 dark:border-[#2A2A2A] px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-[#151515]">
                <p class="text-xs text-slate-500 dark:text-gray-400">
                    Menampilkan <span class="font-bold text-slate-900 dark:text-white">1-10</span> dari <span id="totalUsers" class="font-bold text-slate-900 dark:text-white">0</span> pengguna
                </p>
                <div class="flex items-center gap-1 text-sm">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors">
                        <i class="ph-bold ph-caret-left"></i>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-kinetic-primary text-slate-900 font-bold transition-colors">
                        1
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium transition-colors">
                        2
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium transition-colors">
                        3
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors">
                        <i class="ph-bold ph-caret-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Add/Edit User -->
    <div id="modalUserForm" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-lg p-8 relative shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">

            <button onclick="closeUserModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors z-50">
                <i class="ph-bold ph-x text-xl"></i>
            </button>

            <div class="mb-6">
                <h3 id="modalTitle" class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Tambah User Baru</h3>
                <p id="modalSubtitle" class="text-xs text-slate-500 dark:text-gray-400">Isi form di bawah untuk menambah user baru.</p>
            </div>

            <form id="userForm" class="space-y-5">
                @csrf
                <input type="hidden" id="userId" name="user_id" value="">

                <!-- Name -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" id="inputName" name="name" placeholder="Nama Lengkap" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors" required>
                    <p id="errorName" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" id="inputEmail" name="email" placeholder="email@example.com" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors" required>
                    <p id="errorEmail" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Password <span id="passwordNote" class="text-slate-400 text-[8px]">(Isi jika ingin mengubah)</span></label>
                    <input type="password" id="inputPassword" name="password" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors">
                    <p id="errorPassword" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Unit</label>
                    <select id="inputUnit" name="unit_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none" required>
                        <option value="" disabled selected>Pilih Unit</option>
                    </select>
                    <p id="errorUnit" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Position -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Jabatan <span class="text-slate-400 text-[8px]">(Opsional)</span></label>
                    <select id="inputPosition" name="position_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none">
                        <option value="">Pilih Jabatan</option>
                    </select>
                    <p id="errorPosition" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Role</label>
                    <select id="inputRole" name="role_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none" required>
                        <option value="" disabled selected>Pilih Role</option>
                    </select>
                    <p id="errorRole" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A] mt-6">
                    <button type="button" onclick="closeUserModal()" class="w-1/3 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3.5 rounded-xl transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="w-2/3 bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(20,184,166,0.2)] text-sm">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
        
    </div>

    <!-- Modal Delete Confirmation -->
    <div id="modalDeleteConfirm" class="hidden fixed inset-0 z-[98] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-sm p-8 relative shadow-2xl transform scale-95 transition-transform duration-300">

            <button onclick="closeDeleteConfirm()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x text-xl"></i>
            </button>

            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-[#2A1515] flex items-center justify-center mx-auto mb-4">
                    <i class="ph-bold ph-warning text-3xl text-red-500"></i>
                </div>
                <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-2">Hapus User?</h3>
                <p class="text-sm text-slate-500 dark:text-gray-400">
                    Anda yakin ingin menghapus user <span id="deleteUserName" class="font-bold text-slate-900 dark:text-white"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteConfirm()" class="w-1/2 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3 rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="button" onclick="confirmDelete()" id="confirmDeleteBtn" class="w-1/2 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition-colors text-sm">
                    Hapus User
                </button>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
let currentEditUserId = null;
let currentDeleteUserId = null;
let userToDeleteName = null;

// Get CSRF token safely
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        console.warn('CSRF token not found in meta tags');
        return '';
    }
    return token.getAttribute('content') || '';
}

// Safe element getter
function getElement(id) {
    const el = document.getElementById(id);
    if (!el) console.warn(`Element with id "${id}" not found`);
    return el;
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded - starting to load data');
    const userForm = getElement('userForm');
    if (userForm) {
        setupFormSubmit();
    }
    loadUsers();
    loadUnitsAndRoles();
});

// Load users from database
function loadUsers() {
    showLoading(true);
    const csrfToken = getCsrfToken();
    
    fetch('/admin/api/users', {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': csrfToken
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Users data loaded:', data);
        showLoading(false);
        
        if (data.success && data.data) {
            renderUsersTable(data.data.data || data.data);
            const totalUsersEl = getElement('totalUsers');
            if (totalUsersEl) {
                totalUsersEl.textContent = (data.data.total || data.data.length);
            }
        } else {
            showAlert('Gagal memuat data users: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('Error loading users:', error);
        showAlert('Terjadi kesalahan saat memuat data: ' + error.message, 'error');
    });
}

// Render users table
function renderUsersTable(users) {
    const tbody = getElement('usersTableBody');
    if (!tbody) return;
    
    if (!users || users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-gray-400">
                    Tidak ada data user
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = users.map(user => {
        const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        const roleColor = getRoleColor(user.role?.name);
        const levelColor = getLevelColor(user.unit?.level);
        
        return `
            <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group cursor-pointer">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-[#0D2A27] text-teal-600 dark:text-kinetic-primary flex items-center justify-center text-sm font-bold border border-teal-100 dark:border-teal-900/50">
                            ${initials}
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">${user.name}</h4>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-gray-300">${user.email}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-slate-100 dark:bg-[#222] text-slate-600 dark:text-gray-300 rounded-full text-xs font-medium border border-slate-200 dark:border-[#333]">
                        ${user.unit?.unit_name || 'N/A'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 ${levelColor} rounded-full text-xs font-medium border">
                        ${user.unit?.level || 'N/A'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5 ${roleColor}">
                        <i class="ph-fill ph-user text-base"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">${user.role?.name || 'N/A'}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="editUser(${user.id})" class="p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/20 text-blue-600 dark:text-blue-400 transition-colors" title="Edit">
                            <i class="ph-bold ph-pencil text-lg"></i>
                        </button>
                        <button onclick="openDeleteConfirm(${user.id}, '${user.name}')" class="p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors" title="Hapus">
                            <i class="ph-bold ph-trash text-lg"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Get role color class
function getRoleColor(roleName) {
    const colors = {
        'SuperAdmin': 'text-teal-600 dark:text-kinetic-primary',
        'Admin_Unit': 'text-cyan-600 dark:text-kinetic-tertiary',
        'Approver': 'text-purple-600 dark:text-purple-400',
        'User': 'text-slate-500 dark:text-gray-500'
    };
    return colors[roleName] || 'text-slate-500 dark:text-gray-500';
}

// Get level color class
function getLevelColor(levelName) {
    const colors = {
        'Pusat': 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900',
        'Jurusan': 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900',
        'Organisasi': 'bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-900'
    };
    return colors[levelName] || 'bg-slate-100 dark:bg-slate-900/20 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-900';
}

// Load units and roles
function loadUnitsAndRoles() {
    const unitSelect = document.getElementById('inputUnit');
    const positionSelect = document.getElementById('inputPosition');
    const roleSelect = document.getElementById('inputRole');

    if (!unitSelect || !positionSelect || !roleSelect) {
        console.error('Form elements not found');
        return;
    }

    // Load units
    fetch('/admin/api/units', {
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => {
        if (!r.ok) throw new Error(`Units API error: ${r.status}`);
        return r.json();
    })
    .then(data => {
        if (data.data || data.units) {
            const units = data.data || data.units;
            units.forEach(unit => {
                const option = document.createElement('option');
                option.value = unit.id;
                option.textContent = unit.unit_name || unit.name;
                unitSelect.appendChild(option);
            });
        }
    })
    .catch(e => console.error('Error loading units:', e));

    // Load positions
    fetch('/admin/api/positions', {
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => {
        if (!r.ok) throw new Error(`Positions API error: ${r.status}`);
        return r.json();
    })
    .then(data => {
        if (data.data || data.positions) {
            const positions = data.data || data.positions;
            positions.forEach(pos => {
                const option = document.createElement('option');
                option.value = pos.id;
                option.textContent = pos.name;
                positionSelect.appendChild(option);
            });
        }
    })
    .catch(e => console.error('Error loading positions:', e));

    // Load roles
    fetch('/admin/api/roles', {
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => {
        if (!r.ok) throw new Error(`Roles API error: ${r.status}`);
        return r.json();
    })
    .then(data => {
        if (data.data || data.roles) {
            const roles = data.data || data.roles;
            roles.forEach(role => {
                const option = document.createElement('option');
                option.value = role.id;
                option.textContent = role.name;
                roleSelect.appendChild(option);
            });
        }
    })
    .catch(e => console.error('Error loading roles:', e));
}

// Open add user modal
function openAddUserModal() {
    currentEditUserId = null;
    const userId = getElement('userId');
    const userForm = getElement('userForm');
    const modalTitle = getElement('modalTitle');
    const modalSubtitle = getElement('modalSubtitle');
    const passwordNote = getElement('passwordNote');
    const inputPassword = getElement('inputPassword');
    const submitBtn = getElement('submitBtn');
    
    if (!userId || !userForm || !modalTitle || !submitBtn) return;
    
    userId.value = '';
    userForm.reset();
    modalTitle.textContent = 'Tambah User Baru';
    if (modalSubtitle) modalSubtitle.textContent = 'Isi form di bawah untuk menambah user baru.';
    if (passwordNote) passwordNote.textContent = '(Wajib diisi)';
    if (inputPassword) inputPassword.required = true;
    submitBtn.textContent = 'Tambah User';
    clearErrors();
    openModal('modalUserForm');
}

// Edit user
function editUser(userId) {
    currentEditUserId = userId;
    
    fetch(`/admin/api/users/${userId}`, {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data) {
            const user = data.data;
            const inputUserId = getElement('userId');
            const inputName = getElement('inputName');
            const inputEmail = getElement('inputEmail');
            const inputUnit = getElement('inputUnit');
            const inputPosition = getElement('inputPosition');
            const inputRole = getElement('inputRole');
            const modalTitle = getElement('modalTitle');
            const modalSubtitle = getElement('modalSubtitle');
            const passwordNote = getElement('passwordNote');
            const inputPassword = getElement('inputPassword');
            const submitBtn = getElement('submitBtn');
            
            if (!inputUserId || !inputName || !inputEmail) return;
            
            inputUserId.value = user.id;
            inputName.value = user.name;
            inputEmail.value = user.email;
            if (inputUnit) inputUnit.value = user.unit_id;
            if (inputPosition) inputPosition.value = user.position_id || '';
            if (inputRole) inputRole.value = user.role_id;
            
            if (modalTitle) modalTitle.textContent = 'Edit User';
            if (modalSubtitle) modalSubtitle.textContent = 'Ubah informasi user di bawah.';
            if (passwordNote) passwordNote.textContent = '(Isi jika ingin mengubah)';
            if (inputPassword) inputPassword.required = false;
            if (submitBtn) submitBtn.textContent = 'Simpan Perubahan';
            clearErrors();
            openModal('modalUserForm');
        } else {
            showAlert('Gagal memuat data user', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'error');
    });
}

// Setup form submit
function setupFormSubmit() {
    const userForm = getElement('userForm');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitUserForm();
        });
    }
}

// Submit user form
function submitUserForm() {
    const userId = getElement('userId');
    const inputName = getElement('inputName');
    const inputEmail = getElement('inputEmail');
    const inputPassword = getElement('inputPassword');
    const inputUnit = getElement('inputUnit');
    const inputPosition = getElement('inputPosition');
    const inputRole = getElement('inputRole');
    
    if (!userId || !inputName || !inputEmail || !inputPassword || !inputUnit || !inputRole) {
        showAlert('Form elements tidak ditemukan', 'error');
        return;
    }
    
    const userIdValue = userId.value;
    const isEdit = userIdValue && userIdValue !== '';
    const csrfToken = getCsrfToken();
    
    const formData = {
        name: inputName.value,
        email: inputEmail.value,
        unit_id: inputUnit.value,
        position_id: inputPosition.value || null,
        role_id: inputRole.value,
    };

    const password = inputPassword.value;
    if (password) {
        formData.password = password;
    } else if (!isEdit) {
        showAlert('Password tidak boleh kosong untuk user baru', 'error');
        return;
    }

    const url = isEdit 
        ? `/admin/api/users/${userIdValue}`
        : '/admin/api/users';
    
    const method = isEdit ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) return response.json().then(e => { throw e; });
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closeUserModal();
            loadUsers();
        } else {
            showAlert(data.message || 'Gagal menyimpan', 'error');
            if (data.errors) displayFormErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            displayFormErrors(error.errors);
        } else {
            showAlert(error.message || 'Terjadi kesalahan', 'error');
        }
    });
}

// Open delete confirmation
function openDeleteConfirm(userId, userName) {
    currentDeleteUserId = userId;
    userToDeleteName = userName;
    const deleteUserName = getElement('deleteUserName');
    if (deleteUserName) {
        deleteUserName.textContent = userName;
    }
    openModal('modalDeleteConfirm');
}

// Close delete confirmation
function closeDeleteConfirm() {
    currentDeleteUserId = null;
    userToDeleteName = null;
    closeModal('modalDeleteConfirm');
}

// Confirm delete
function confirmDelete() {
    if (!currentDeleteUserId) return;
    const csrfToken = getCsrfToken();

    fetch(`/admin/api/users/${currentDeleteUserId}`, {
        method: 'DELETE',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) return response.json().then(e => { throw e; });
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closeDeleteConfirm();
            loadUsers();
        } else {
            showAlert(data.message || 'Gagal menghapus', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'Terjadi kesalahan', 'error');
    });
}

// Modal helpers
function openModal(modalId) {
    const modal = getElement(modalId);
    if (!modal) return;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        const innerDiv = modal.querySelector('div');
        if (innerDiv) innerDiv.classList.remove('scale-95');
    }, 10);
}

function closeModal(modalId) {
    const modal = getElement(modalId);
    if (!modal) return;
    
    modal.classList.add('opacity-0');
    const innerDiv = modal.querySelector('div');
    if (innerDiv) innerDiv.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openUserModal() {
    openModal('modalUserForm');
}

function closeUserModal() {
    closeModal('modalUserForm');
}

// Show loading indicator
function showLoading(show) {
    const loader = getElement('loadingIndicator');
    if (!loader) return;
    
    if (show) {
        loader.classList.remove('hidden');
    } else {
        loader.classList.add('hidden');
    }
}

// Show alert
function showAlert(message, type = 'info') {
    const container = document.getElementById('alertContainer');
    
    if (!container) {
        console.warn('Alert container not found');
        return;
    }
    
    const bgColor = type === 'success' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-900' : 
                   type === 'error' ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-900' :
                   'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-900';
    const textColor = type === 'success' ? 'text-green-700 dark:text-green-400' :
                     type === 'error' ? 'text-red-700 dark:text-red-400' :
                     'text-blue-700 dark:text-blue-400';
    const icon = type === 'success' ? 'ph-check-circle' :
                type === 'error' ? 'ph-warning-circle' :
                'ph-info';

    container.innerHTML = `
        <div class="flex items-start gap-3 p-4 rounded-xl border ${bgColor}">
            <i class="ph-bold ${icon} text-xl ${textColor} flex-shrink-0"></i>
            <p class="text-sm ${textColor}">${message}</p>
        </div>
    `;
    container.classList.remove('hidden');

    setTimeout(() => {
        container.classList.add('hidden');
    }, 5000);
}

// Display form errors
function displayFormErrors(errors) {
    clearErrors();
    Object.keys(errors).forEach(field => {
        const fieldKey = field.charAt(0).toUpperCase() + field.slice(1);
        const errorElement = getElement(`error${fieldKey}`);
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove('hidden');
        }
    });
}

// Clear form errors
function clearErrors() {
    document.querySelectorAll('[id^="error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
}
</script>
