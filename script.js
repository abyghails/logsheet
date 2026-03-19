document.addEventListener('DOMContentLoaded', () => {
    // Konstanta URL Global untuk mengakses Web App Google Apps Script
    const SCRIPT_URL = 'https://script.google.com/macros/s/AKfycbzmipqvh5MDhPTsEnPCTz0g-2-PvLvK170pHQJyRt6R6bWQbWPiTJ-vC4xkBc-wEeJW/exec';

    const addLogBtn = document.getElementById('addLogBtn');
    const modal = document.getElementById('addLogModal');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const form = document.getElementById('logForm');
    const saveBtn = document.getElementById('saveLogBtn');
    const globalLoader = document.getElementById('globalLoader');
    const formAction = document.getElementById('formAction');
    const formRowIndex = document.getElementById('formRowIndex');
    const formSheetName = document.getElementById('formSheetName');
    const formCategory = document.getElementById('formCategory');
    const modalTitle = modal ? modal.querySelector('.modal-header h2') : null;

    // Reset Form to Add Mode
    const setAddMode = () => {
        const catVal = formCategory ? formCategory.value : '';
        if(form) form.reset();
        if(formCategory) formCategory.value = catVal; // Restore category
        if(formAction) formAction.value = 'add';
        if(formRowIndex) formRowIndex.value = '';
        if(formSheetName) formSheetName.value = '';
        if(modalTitle) modalTitle.innerText = "Catat Data Log Baru";
    };

    // Open Modal (Add mode)
    if(addLogBtn) {
        addLogBtn.addEventListener('click', () => {
            setAddMode();
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close Modal
    const closeModal = () => {
        if(modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if(closeBtn) closeBtn.addEventListener('click', closeModal);
    if(cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if(modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    // Save Data -> Apps Script -> Refresh AJAX
    function resetBtnState(originalText) {
        if(saveBtn) {
            saveBtn.innerHTML = originalText;
            saveBtn.style.backgroundColor = '';
            saveBtn.style.opacity = '1';
            saveBtn.disabled = false;
        }
        if(globalLoader) globalLoader.classList.remove('active');
    }

    function refreshTableData(originalBtnText, successMessage = null) {
        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const currentContainer = document.querySelector('.table-container');
                const newContainer = doc.querySelector('.table-container');
                
                if (currentContainer && newContainer) {
                    currentContainer.innerHTML = newContainer.innerHTML;
                }
                
                closeModal();
                resetBtnState(originalBtnText);
                
                if (successMessage !== false) {
                    if (!successMessage) {
                        const isUpdate = formAction && formAction.value === 'update';
                        successMessage = `Data log berhasil ${isUpdate ? 'diperbarui' : 'ditambahkan'}!`;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: successMessage,
                        confirmButtonColor: '#22c55e',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(err => {
                console.error("Gagal refresh ajax:", err);
                window.location.reload(); 
            });
    }

    if(saveBtn && form) {
        saveBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            if (!formData.get('Tanggal') || !formData.get('NAMA ENGINERING')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Tidak Lengkap',
                    text: 'Mohon isi Tanggal dan Nama Engineering terlebih dahulu.',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = 'Memproses...';
            saveBtn.style.opacity = '0.7';
            saveBtn.disabled = true;
            if (globalLoader) globalLoader.classList.add('active');
            
            // Konversi FormData ke URLSearchParams agar lebih konsisten di terima Google Apps Script
            const encodedData = new URLSearchParams(formData);
            // Ensure no empty fields from URLSearchParams
            if(formCategory && !encodedData.has('category')) {
                encodedData.append('category', formCategory.value);
            }
            
            fetch(SCRIPT_URL, { 
                method: 'POST', 
                body: encodedData.toString(),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                mode: 'no-cors' 
            })
            .then(() => {
                saveBtn.innerHTML = 'Tersimpan!';
                saveBtn.style.backgroundColor = 'var(--success-icon)';
                
                // Tunggu 1.5 detik agar script merespon di google sheet
                setTimeout(() => {
                    refreshTableData(originalText);
                }, 1500);
            })
            .catch(error => {
                console.error('Error!', error.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Gagal',
                    text: 'Terjadi kesalahan saat memproses data. Periksa pengaturan script Anda.',
                    confirmButtonColor: '#ef4444'
                });
                resetBtnState(originalText);
            });
        });
    }

    // ==========================================
    // Edit Modal Logic
    // ==========================================
    window.showEditModal = function(btn) {
        if(!modal || !form) return;
        const dataStr = btn.getAttribute('data-row');
        if(!dataStr) return;
        
        try {
            const dataPair = JSON.parse(dataStr);
            setAddMode(); // Reset dulu
            
            if(formAction) formAction.value = 'update';
            if(formRowIndex) formRowIndex.value = dataPair['!row_index'];
            if(formSheetName) formSheetName.value = dataPair['!sheet_name'];
            if(modalTitle) modalTitle.innerText = "Edit Data Log (Baris ke-" + dataPair['!row_index'] + ")";
            
            for (const [key, value] of Object.entries(dataPair)) {
                if (key === '!row_index') continue;
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = value;
                }
            }
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
        } catch(e) {
            console.error("Error inject form", e);
        }
    };

    // ==========================================
    // Detail Modal Logic
    // ==========================================
    const detailLogModal = document.getElementById('detailLogModal');
    const closeDetailModalBtn = document.getElementById('closeDetailModalBtn');
    const closeDetailBtn = document.getElementById('closeDetailBtn');
    const detailModalBody = document.getElementById('detailModalBody');

    window.showDetailModal = function(btn) {
        if(!detailLogModal || !detailModalBody) return;
        const dataStr = btn.getAttribute('data-row');
        if(!dataStr) return;
        try {
            const dataPair = JSON.parse(dataStr);
            let html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">';
            for (const [key, value] of Object.entries(dataPair)) {
                if(key === '!row_index' || key === '!sheet_name') continue;
                html += `
                    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-bottom: 6px; text-transform: uppercase;">${key}</div>
                        <div style="font-size: 0.95rem; color: #1e293b; font-weight: 500; word-break: break-word;">${value || '-'}</div>
                    </div>
                `;
            }
            html += '</div>';
            detailModalBody.innerHTML = html;
            detailLogModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        } catch (e) { 
            console.error("Error", e); 
        }
    };

    const closeDetail = () => {
        if(detailLogModal) {
            detailLogModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    if(closeDetailModalBtn) closeDetailModalBtn.addEventListener('click', closeDetail);
    if(closeDetailBtn) closeDetailBtn.addEventListener('click', closeDetail);
    if(detailLogModal) detailLogModal.addEventListener('click', (e) => { if (e.target === detailLogModal) closeDetail(); });

    // ==========================================
    // Delete Logic
    // ==========================================
    window.deleteLog = function(btn) {
        const dataStr = btn.getAttribute('data-row');
        if(!dataStr) return;
        
        try {
            const dataPair = JSON.parse(dataStr);
            const rowIndex = dataPair['!row_index'];
            const sheetName = dataPair['!sheet_name'];
            
            Swal.fire({
                title: 'Hapus Data Log?',
                text: `Anda akan menghapus rekaman data pada baris ke-${rowIndex} di sheet '${sheetName}'. Aksi ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (globalLoader) {
                        globalLoader.classList.add('active');
                        const loaderText = document.getElementById('loaderText');
                        if (loaderText) loaderText.innerText = "Menghapus data...";
                    }
                    
                    const formData = new URLSearchParams();
                    formData.append('action', 'delete');
                    formData.append('rowIndex', rowIndex);
                    formData.append('sheetName', sheetName);
                    if(formCategory) formData.append('category', formCategory.value);
                    
                    fetch(SCRIPT_URL, { 
                        method: 'POST', 
                        body: formData.toString(),
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        mode: 'no-cors' 
                    })
                    .then(() => {
                        setTimeout(() => refreshTableData('Kirim ke Google Sheet', 'Data log tersebut telah dikosongkan secara permanen.'), 1500);
                    })
                    .catch(error => {
                        console.error('Delete Error!', error.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Penghapusan Gagal',
                            text: 'Gagal menghapus data. Periksa koneksi atau URL Script Anda.',
                            confirmButtonColor: '#ef4444'
                        });
                        if (globalLoader) globalLoader.classList.remove('active');
                    });
                }
            });
        } catch(e) {
            console.error("Error Delete Function", e);
        }
    };

    // Sidebar Toggle Logic (v2.4)
    const navGroups = document.querySelectorAll('.nav-group');
    navGroups.forEach(group => {
        const header = group.querySelector('.nav-item');
        if (header) {
            header.addEventListener('click', () => {
                group.classList.toggle('open');
            });
        }
    });
});
