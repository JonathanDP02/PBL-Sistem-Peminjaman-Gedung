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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Total Pengguna</p>
                <div class="flex items-end gap-2">
                    <h2 id="statTotalUsers" class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">0</h2>
                    <span class="text-xs font-bold text-teal-600 dark:text-kinetic-primary mb-0.5" id="statTotalUsersLabel">Semua Role</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Unit Admin</p>
                <div class="flex items-end gap-2">
                    <h2 id="statUnitAdmin" class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">0</h2>
                    <span class="text-xs font-medium text-slate-500 dark:text-gray-400 mb-0.5">Tersebar</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Akun Aktif Sekarang</p>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-kinetic-primary animate-pulse"></span>
                    <h2 id="statActiveNow" class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">0</h2>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4 shadow-sm dark:shadow-none transition-colors">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="ph-bold ph-magnifying-glass text-slate-400 dark:text-gray-500"></i>
                    </span>
                    <input type="text" id="filterSearch" placeholder="Cari nama..." class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-[#1C1C1C] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kinetic-primary focus:border-transparent transition-all">
                </div>

                <!-- Unit Filter -->
                <div>
                    <select id="filterUnit" class="w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-[#1C1C1C] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-kinetic-primary focus:border-transparent transition-all">
                        <option value="">Semua Unit</option>
                    </select>
                </div>

                <!-- Level Filter -->
                <div>
                    <select id="filterLevel" class="w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-[#1C1C1C] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-kinetic-primary focus:border-transparent transition-all">
                        <option value="">Semua Level</option>
                        <option value="Pusat">Pusat</option>
                        <option value="Jurusan">Jurusan</option>
                        <option value="Organisasi">Organisasi</option>
                    </select>
                </div>

                <!-- Role Filter -->
                <div>
                    <select id="filterRole" class="w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-[#1C1C1C] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-kinetic-primary focus:border-transparent transition-all">
                        <option value="">Semua Role</option>
                    </select>
                </div>

                <!-- Sort Name Filter -->
                <div>
                    <select id="filterSortName" class="w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-[#1C1C1C] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-kinetic-primary focus:border-transparent transition-all">
                        <option value="asc">Nama: A - Z</option>
                        <option value="desc">Nama: Z - A</option>
                    </select>
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
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Unit</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Level</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Role</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody id="usersTableBody" class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                        <!-- Data akan dimuat oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 dark:border-[#2A2A2A] px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-[#151515]">
                <p class="text-xs text-slate-500 dark:text-gray-400" id="paginationInfo">
                    Menampilkan <span class="font-bold text-slate-900 dark:text-white">0</span> dari <span class="font-bold text-slate-900 dark:text-white">0</span> pengguna
                </p>
                <div class="flex items-center gap-1 text-sm" id="paginationControls">
                    <!-- Dynamic Pagination Controls -->
                </div>
            </div>
        </div>

    </div>

    @push('modals')
        <!-- Data Modals -->
        @include('user.admin.modals.modal-user-form')
        @include('user.admin.modals.modal-delete-confirm')
        @include('user.admin.modals.modal-view-user')
    @endpush

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

let currentSearch = '';
let currentUnitId = '';
let currentLevel = '';
let currentRoleId = '';
let currentSortName = 'asc';
let currentPage = 1;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded - starting to load data');
    const userForm = getElement('userForm');
    if (userForm) {
        setupFormSubmit();
    }
    setupFilterListeners();
    loadUsers(1);
    loadUnitsAndRoles();

    // Auto capitalize each word on custom position input when focus leaves
    const inputPositionCustom = document.getElementById('inputPositionCustom');
    if (inputPositionCustom) {
        inputPositionCustom.addEventListener('blur', function() {
            this.value = this.value.split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' ');
        });
    }
});

// Setup event listeners for filtering
function setupFilterListeners() {
    const searchInput = getElement('filterSearch');
    const unitSelect = getElement('filterUnit');
    const levelSelect = getElement('filterLevel');
    const roleSelect = getElement('filterRole');
    const sortSelect = getElement('filterSortName');

    let debounceTimeout = null;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                currentSearch = this.value.toLowerCase();
                loadUsers(1);
            }, 300);
        });
    }

    if (unitSelect) {
        unitSelect.addEventListener('change', function() {
            currentUnitId = this.value;
            loadUsers(1);
        });
    }

    if (levelSelect) {
        levelSelect.addEventListener('change', function() {
            currentLevel = this.value;
            loadUsers(1);
        });
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            currentRoleId = this.value;
            loadUsers(1);
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentSortName = this.value;
            loadUsers(1);
        });
    }
}

// Load users from database with filters and pagination
function loadUsers(page = 1) {
    currentPage = page;
    showLoading(true);
    const csrfToken = getCsrfToken();
    
    const params = new URLSearchParams({
        page: page,
        search: currentSearch,
        unit_id: currentUnitId,
        level: currentLevel,
        role_id: currentRoleId,
        sort_name: currentSortName
    });

    fetch(`/admin/api/users?${params.toString()}`, {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        showLoading(false);
        
        if (data.success && data.data) {
            renderUsersTable(data.data.data || []);
            renderPagination(data.data);
            
            // Update stats
            if (data.stats) {
                const statTotal = getElement('statTotalUsers');
                if (statTotal) statTotal.textContent = data.stats.total;
                
                const statUnit = getElement('statUnitAdmin');
                if (statUnit) statUnit.textContent = data.stats.unit_admin;
                
                const statActive = getElement('statActiveNow');
                if (statActive) statActive.textContent = data.stats.active_now;
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

// Render dynamic pagination controls
function renderPagination(paginationData) {
    const infoEl = getElement('paginationInfo');
    const controlsEl = getElement('paginationControls');
    
    if (!paginationData) return;

    const from = paginationData.from || 0;
    const to = paginationData.to || 0;
    const total = paginationData.total || 0;
    const currentPage = paginationData.current_page || 1;
    const lastPage = paginationData.last_page || 1;

    // Update info text
    if (infoEl) {
        infoEl.innerHTML = `
            Menampilkan <span class="font-bold text-slate-900 dark:text-white">${from}-${to}</span> dari <span class="font-bold text-slate-900 dark:text-white">${total}</span> pengguna
        `;
    }

    if (!controlsEl) return;
    
    if (total === 0) {
        controlsEl.innerHTML = '';
        return;
    }

    let html = '';

    // Previous Page Button
    const prevDisabled = currentPage === 1;
    html += `
        <button onclick="${prevDisabled ? '' : `loadUsers(${currentPage - 1})`}" 
            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors ${prevDisabled ? 'opacity-40 cursor-not-allowed' : ''}">
            <i class="ph-bold ph-caret-left"></i>
        </button>
    `;

    // Dynamic Page Numbers
    const delta = 2;
    const range = [];
    for (let i = Math.max(2, currentPage - delta); i <= Math.min(lastPage - 1, currentPage + delta); i++) {
        range.push(i);
    }

    if (currentPage - delta > 2) {
        range.unshift('...');
    }
    if (currentPage + delta < lastPage - 1) {
        range.push('...');
    }

    range.unshift(1);
    if (lastPage > 1) {
        range.push(lastPage);
    }

    range.forEach(page => {
        if (page === '...') {
            html += `
                <span class="w-8 h-8 flex items-center justify-center text-slate-400 dark:text-gray-500">
                    ...
                </span>
            `;
        } else {
            const isCurrent = page === currentPage;
            html += `
                <button onclick="loadUsers(${page})" 
                    class="w-8 h-8 flex items-center justify-center rounded-lg ${isCurrent ? 'bg-kinetic-primary text-slate-900 font-bold' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium'} transition-colors">
                    ${page}
                </button>
            `;
        }
    });

    // Next Page Button
    const nextDisabled = currentPage === lastPage;
    html += `
        <button onclick="${nextDisabled ? '' : `loadUsers(${currentPage + 1})`}" 
            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors ${nextDisabled ? 'opacity-40 cursor-not-allowed' : ''}">
            <i class="ph-bold ph-caret-right"></i>
        </button>
    `;

    controlsEl.innerHTML = html;
}

// Render users table
function renderUsersTable(users) {
    const tbody = getElement('usersTableBody');
    if (!tbody) return;
    
    if (!users || users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-gray-400">
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
            <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group cursor-pointer" onclick="viewUser(${user.id})">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-[#0D2A27] text-teal-600 dark:text-kinetic-primary flex items-center justify-center text-sm font-bold border border-teal-100 dark:border-teal-900/50">
                            ${initials}
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">${user.name}</h4>
                    </div>
                </td>
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
                        <button onclick="event.stopPropagation(); editUser(${user.id})" class="p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/20 text-blue-600 dark:text-blue-400 transition-colors" title="Edit">
                            <i class="ph-bold ph-pencil text-lg"></i>
                        </button>
                        <button onclick="event.stopPropagation(); openDeleteConfirm(${user.id}, '${user.name.replace(/'/g, "\\'")}')" class="p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors" title="Hapus">
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
        'Administrator Utama': 'text-teal-600 dark:text-kinetic-primary',
        'Administrator Unit': 'text-cyan-600 dark:text-kinetic-tertiary',
        'Penyetuju': 'text-purple-600 dark:text-purple-400',
        'Peminjam': 'text-slate-500 dark:text-gray-500'
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
    const filterUnit = document.getElementById('filterUnit');
    const filterRole = document.getElementById('filterRole');

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
                const name = unit.unit_name || unit.name;
                
                // Populates modal select
                if (unitSelect) {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = name;
                    unitSelect.appendChild(option);
                }

                // Populates filter select
                if (filterUnit) {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = name;
                    filterUnit.appendChild(option);
                }
            });
        }
    })
    .catch(e => console.error('Error loading units:', e));

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
                if (positionSelect) {
                    const option = document.createElement('option');
                    option.value = pos.id;
                    option.textContent = pos.name;
                    positionSelect.appendChild(option);
                }
            });
        }
    })
    .catch(e => console.error('Error loading positions:', e));

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
                // Populates modal select
                if (roleSelect) {
                    const option = document.createElement('option');
                    option.value = role.id;
                    option.textContent = role.name;
                    roleSelect.appendChild(option);
                }

                // Populates filter select
                if (filterRole) {
                    const option = document.createElement('option');
                    option.value = role.id;
                    option.textContent = role.name;
                    filterRole.appendChild(option);
                }
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
    resetPositionMode();
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
            resetPositionMode();
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
    
    // Check if custom input mode is active
    const isCustomMode = !document.getElementById('containerPositionInput').classList.contains('hidden');
    const positionValue = isCustomMode 
        ? (document.getElementById('inputPositionCustom').value.trim() || null)
        : (inputPosition.value || null);
    
    const formData = {
        name: inputName.value,
        email: inputEmail.value,
        unit_id: inputUnit.value,
        position_id: positionValue,
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

// View User Details
function viewUser(userId) {
    showLoading(true);
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
        showLoading(false);
        if (data.success && data.data) {
            const user = data.data;
            const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
            const roleColor = getRoleColor(user.role?.name);
            
            getElement('viewUserInitials').textContent = initials;
            getElement('viewUserName').textContent = user.name;
            getElement('viewUserEmail').textContent = user.email;
            
            const roleEl = getElement('viewUserRole');
            roleEl.textContent = user.role?.name || '-';
            
            // Apply role colors classes dynamically
            roleEl.className = 'px-3 py-1 rounded-full text-xs font-medium border border-slate-200 dark:border-[#333]'; // reset base
            
            if (user.role?.name === 'Administrator Utama') {
                roleEl.classList.add('bg-teal-50', 'dark:bg-[#0D2A27]', 'text-teal-700', 'dark:text-kinetic-primary');
            } else if (user.role?.name === 'Administrator Unit') {
                roleEl.classList.add('bg-cyan-50', 'dark:bg-cyan-900/20', 'text-cyan-700', 'dark:text-cyan-400');
            } else if (user.role?.name === 'Penyetuju') {
                roleEl.classList.add('bg-purple-50', 'dark:bg-purple-900/20', 'text-purple-700', 'dark:text-purple-400');
            } else {
                roleEl.classList.add('bg-slate-100', 'dark:bg-[#222]', 'text-slate-600', 'dark:text-gray-300');
            }
            
            getElement('viewUserUnit').textContent = user.unit?.unit_name || '-';
            getElement('viewUserLevel').textContent = user.unit?.level || '-';
            getElement('viewUserPosition').textContent = user.position?.name || '-';
            
            openModal('modalViewUser');
        } else {
            showAlert('Gagal memuat detail user', 'error');
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('Error:', error);
        showAlert('Terjadi kesalahan memuat detail user', 'error');
    });
}

function closeViewUserModal() {
    closeModal('modalViewUser');
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

// Toggle Position Input Mode between Dropdown Select and Custom Text Input
function togglePositionMode() {
    const containerSelect = document.getElementById('containerPositionSelect');
    const containerInput = document.getElementById('containerPositionInput');
    const btnToggle = document.getElementById('btnTogglePositionMode');
    
    if (containerSelect.classList.contains('hidden')) {
        // Switch to Select Dropdown Mode
        containerSelect.classList.remove('hidden');
        containerInput.classList.add('hidden');
        btnToggle.textContent = '+ Buat Baru';
        document.getElementById('inputPositionCustom').value = '';
    } else {
        // Switch to Custom Input Text Mode
        containerSelect.classList.add('hidden');
        containerInput.classList.remove('hidden');
        btnToggle.textContent = '← Pilih Jabatan';
        document.getElementById('inputPosition').value = '';
    }
}

// Reset Position Input Mode to default Dropdown Select
function resetPositionMode() {
    const containerSelect = document.getElementById('containerPositionSelect');
    const containerInput = document.getElementById('containerPositionInput');
    const btnToggle = document.getElementById('btnTogglePositionMode');
    
    if (containerSelect) containerSelect.classList.remove('hidden');
    if (containerInput) containerInput.classList.add('hidden');
    if (btnToggle) btnToggle.textContent = '+ Buat Baru';
    
    const inputCustom = document.getElementById('inputPositionCustom');
    const inputSelect = document.getElementById('inputPosition');
    if (inputCustom) inputCustom.value = '';
    if (inputSelect) inputSelect.value = '';
}
</script>
