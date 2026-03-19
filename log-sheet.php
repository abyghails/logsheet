<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pencatatan Log (Log Sheet) - Prominence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            background-color: white;
            border-bottom-left-radius: var(--radius-lg);
            border-bottom-right-radius: var(--radius-lg);
        }
        .page-info {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .page-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-page {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-page:hover {
            background-color: #f1f5f9;
        }
        .btn-page.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .header-actions-extended {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-area">
                <div class="logo-icon">PR</div>
                <h2>Prominence</h2>
            </div>
            <?php
            $activeCat = isset($_GET['cat']) ? $_GET['cat'] : 'UTILITAS HARIAN';
            $categories = [
                'UTILITAS HARIAN' => 'Utilitas Harian',
                'PUTM' => 'PUTM',
                'PUTR' => 'PUTR',
                'FIRE PUMP' => 'Fire Pump',
                'GWT' => 'GWT',
                'ELEVATOR' => 'Elevator',
                'GENSET' => 'Genset',
                'GONDOLA' => 'Checklist Gondola',
                'SWMS' => 'SWMS',
                'ESF' => 'Equipment Service Form (ESF)'
            ];
            ?>
            <nav class="nav-menu">
                <a href="index.php" class="nav-item">
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20" width="20" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>
                
                <div class="nav-group <?php echo ($activeCat !== 'SWMS') ? 'open' : ''; ?>">
                    <div class="nav-item <?php echo ($activeCat !== 'SWMS') ? 'active' : ''; ?>" style="cursor: pointer;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Log Sheet
                        </div>
                        <svg class="chevron" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="nav-submenu">
                        <?php foreach($categories as $slug => $label): ?>
                            <?php if($slug === 'SWMS' || $slug === 'ESF') continue; ?>
                            <a href="log-sheet.php?cat=<?php echo urlencode($slug); ?>" 
                               class="nav-submenu-item <?php echo $activeCat === $slug ? 'active' : ''; ?>">
                               <?php echo $label; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="nav-group <?php echo ($activeCat === 'SWMS') ? 'open' : ''; ?>">
                    <div class="nav-item <?php echo ($activeCat === 'SWMS') ? 'active' : ''; ?>" style="cursor: pointer;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            Konstruksi & K3
                        </div>
                        <svg class="chevron" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="nav-submenu">
                        <a href="log-sheet.php?cat=SWMS" class="nav-submenu-item <?php echo $activeCat === 'SWMS' ? 'active' : ''; ?>">SWMS</a>
                    </div>
                </div>

                <div class="nav-group <?php echo ($activeCat === 'ESF') ? 'open' : ''; ?>">
                    <div class="nav-item <?php echo ($activeCat === 'ESF') ? 'active' : ''; ?>" style="cursor: pointer;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                            Peralatan
                        </div>
                        <svg class="chevron" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="nav-submenu">
                        <a href="log-sheet.php?cat=ESF" class="nav-submenu-item <?php echo $activeCat === 'ESF' ? 'active' : ''; ?>">Equipment Service Form (ESF)</a>
                    </div>
                </div>

                <a href="#" class="nav-item">
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20" width="20" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Pengaturan
                </a>
            </nav>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Admin+Operator&background=4f46e5&color=fff" alt="User">
                <div class="user-info">
                    <p class="name">Admin Operator</p>
                    <p class="role">System Administrator</p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div>
                    <h1>Log Sheet: <?php echo $categories[$activeCat] ?? 'Utilitas'; ?></h1>
                    <p class="subtitle">Manajemen dan peninjauan data operasional sistem <?php echo strtolower($categories[$activeCat] ?? 'utilitas'); ?></p>
                </div>
                <!-- Extended header actions for log sheet specific view -->
                <div class="header-actions-extended">
                    <div class="search-box">
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="18" width="18" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="date" class="form-control" style="width: auto; height: 38px; border-radius: 2rem; padding-left: 2.5rem; font-family: inherit;">
                    </div>
                    <div class="search-box">
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="18" width="18" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" placeholder="Cari data log...">
                    </div>
                    <button class="btn-secondary">
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="18" width="18" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Export CSV
                    </button>
                    <button class="btn-primary" id="addLogBtn">
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="18" width="18" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Tambah Data Log
                    </button>
                </div>
            </header>

            <!-- Full Page Log Table Container -->
            <div class="table-container">
                <?php
                $scriptUrl = "https://script.google.com/macros/s/AKfycbyGjZafMZaNZsFh5kxSHEmEY7zWjzOP8Zb3CkILSoDZ5WiDZ7DAkpqesxqqKBzIrHAo/exec";
                $sheetParam = isset($_GET['sheet']) ? "&sheetName=".urlencode($_GET['sheet']) : "";
                $catParam = "&category=" . urlencode($activeCat);

                $jsonData = @file_get_contents($scriptUrl . "?action=getData" . $sheetParam . $catParam);
                $response = $jsonData ? json_decode($jsonData, true) : null;

                $headers = $response['headers'] ?? [];
                $rows = $response['data'] ?? [];
                $allSheets = $response['sheets'] ?? [];
                $currentSheet = $response['sheetName'] ?? '-';
                $totalEntries = 0;
                $perPage = 15;
                $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                $totalPages = 1;
                $startIndex = 0;

                if (!empty($rows)) {
                    $rows = array_reverse($rows); // Urutkan terbaru di atas
                    
                    $totalEntries = count($rows);
                    $totalPages = max(1, ceil($totalEntries / $perPage));
                    if ($currentPage > $totalPages) $currentPage = $totalPages;
                    
                    $startIndex = ($currentPage - 1) * $perPage;
                    $rows = array_slice($rows, $startIndex, $perPage);
                }
                ?>

                <div class="table-header">
                    <h2>Sheet Data: <span style="color:var(--primary);"><?php echo htmlspecialchars($currentSheet); ?></span></h2>
                    <div class="table-filters" style="display: flex; gap: 0.5rem; align-items: center;">
                        <a href="https://docs.google.com/spreadsheets/d/19d56GJ59VknxZ_NT0OGnZvS9VWCJ2E-NiR184-NkGcA/export?format=xlsx" download="Log_Report.xlsx" class="btn-secondary" style="display:flex; align-items:center; gap:0.5rem; text-decoration:none; padding: 0.5rem 1rem; color: #10b981; border-color: #10b981; font-weight: 600;">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Excel
                        </a>
                        <a href="https://docs.google.com/spreadsheets/d/19d56GJ59VknxZ_NT0OGnZvS9VWCJ2E-NiR184-NkGcA/export?format=pdf" download="Log_Report.pdf" class="btn-secondary" style="display:flex; align-items:center; gap:0.5rem; text-decoration:none; padding: 0.5rem 1rem; color: #ef4444; border-color: #ef4444; font-weight: 600;">
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            PDF
                        </a>
                        
                        <?php if(!empty($allSheets)): ?>
                        <form method="GET" style="display:inline-block; margin:0;" id="sheetFilterForm">
                            <select name="sheet" class="select-box" onchange="document.getElementById('sheetFilterForm').submit()" style="font-weight:bold;">
                                <?php foreach ($allSheets as $sheetName): ?>
                                    <option value="<?php echo htmlspecialchars($sheetName); ?>" <?php if($sheetName === $currentSheet) echo 'selected'; ?>>
                                        Tab: <?php echo htmlspecialchars($sheetName); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                        <?php endif; ?>
                        
                        <select class="select-box">
                            <option>Semua Status</option>
                            <option>Normal</option>
                            <option>Warning</option>
                        </select>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="log-table">
                        <thead>
                            <tr>
                                <?php if (empty($headers)): ?>
                                    <th>Koneksi Google Sheet Gagal / Data Kosong</th>
                                <?php else: ?>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Engineering</th>
                                    <th>Total (LWBP+WBP)</th>
                                    <th>FWT</th>
                                    <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 3rem;">
                                        Belum ada data masuk atau spreadsheet tidak dapat diakses.<br>
                                        <small class="text-muted">Pastikan link Google Sheet benar dan akses diset ke "Siapa saja yang memiliki link".</small>
                                    </td>
                                </tr>
                            <?php else: 
                                foreach ($rows as $row): 
                                    $jsonStr = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                            ?>
                                    <tr>
                                        <td><span class="badge gray"><?php echo htmlspecialchars($row['Tanggal'] ?? '-'); ?></span></td>
                                        <td class="font-medium"><?php echo htmlspecialchars($row['Jam'] ?? '-'); ?></td>
                                        <td>
                                            <?php 
                                            $engName = $row['NAMA ENGINERING'] ?? '-';
                                            if ($engName !== '-' && preg_match('/^[a-zA-Z\s]+$/', $engName)) {
                                                echo '<div class="operator-cell"><img src="https://ui-avatars.com/api/?name='.urlencode($engName).'&background=random" class="avatar-sm"><span>' . htmlspecialchars($engName) . '</span></div>';
                                            } else {
                                                echo htmlspecialchars($engName);
                                            }
                                            ?>
                                        </td>
                                        <td><span class="status-badge warning"><?php echo htmlspecialchars($row['Total LWBP+WBP'] ?? ($row['Totoal LWBP+WBP'] ?? '-')); ?></span></td>
                                        <td><span class="status-badge success"><?php echo htmlspecialchars($row['FWT'] ?? '-'); ?></span></td>
                                        <td>
                                            <button class="btn-icon" title="Lihat Detail" onclick="showDetailModal(this)" data-row="<?php echo $jsonStr; ?>">
                                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </button>
                                            <button class="btn-icon" title="Edit Data" style="color: var(--primary); margin-left: 4px;" onclick="showEditModal(this)" data-row="<?php echo $jsonStr; ?>">
                                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                            <button class="btn-icon" title="Hapus Data" style="color: var(--critical-icon); margin-left: 4px;" onclick="deleteLog(this)" data-row="<?php echo $jsonStr; ?>">
                                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="16" width="16" xmlns="http://www.w3.org/2000/svg"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-list">
                    <?php if (!empty($rows)): 
                        foreach ($rows as $row): 
                            $jsonStr = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                            $engName = $row['NAMA ENGINERING'] ?? '-';
                    ?>
                        <div class="log-card">
                            <div class="card-header">
                                <div class="card-date">
                                    <span class="label">Pencatatan</span>
                                    <span class="value"><?php echo htmlspecialchars($row['Tanggal'] ?? '-'); ?> · <?php echo htmlspecialchars($row['Jam'] ?? '-'); ?></span>
                                </div>
                                <div class="card-actions">
                                    <button class="btn-icon" title="Lihat Detail" onclick="showDetailModal(this)" data-row="<?php echo $jsonStr; ?>">
                                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="18" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-info-group">
                                    <span class="card-info-label">Engineering</span>
                                    <div class="operator-cell" style="margin-top: 4px;">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($engName); ?>&background=random" class="avatar-sm">
                                        <span class="card-info-value"><?php echo htmlspecialchars($engName); ?></span>
                                    </div>
                                </div>
                                <div class="card-info-group">
                                    <span class="card-info-label">Total (LWBP+WBP)</span>
                                    <span class="status-badge warning" style="margin-top: 4px;"><?php echo htmlspecialchars($row['Total LWBP+WBP'] ?? ($row['Totoal LWBP+WBP'] ?? '-')); ?></span>
                                </div>
                                <div class="card-info-group">
                                    <span class="card-info-label">FWT</span>
                                    <span class="status-badge success" style="margin-top: 4px;"><?php echo htmlspecialchars($row['FWT'] ?? '-'); ?></span>
                                </div>
                                <div class="card-footer-actions" style="grid-column: span 2; display: flex; gap: 8px; margin-top: 10px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                                    <button class="btn-card-action edit" onclick="showEditModal(this)" data-row="<?php echo $jsonStr; ?>">
                                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="14" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit
                                    </button>
                                    <button class="btn-card-action delete" onclick="deleteLog(this)" data-row="<?php echo $jsonStr; ?>">
                                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="14" width="14" xmlns="http://www.w3.org/2000/svg"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div style="text-align: center; padding: 3rem; color: var(--text-muted);">Belum ada data di Spreadsheet.</div>
                    <?php endif; ?>
                </div>
                <!-- Pagination Control -->
                <div class="pagination">
                    <div class="page-info">Menampilkan <?php echo $totalEntries > 0 ? $startIndex + 1 : 0; ?> hingga <?php echo min($startIndex + $perPage, $totalEntries); ?> dari <?php echo number_format($totalEntries); ?> entri data</div>
                    <div class="page-buttons">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?>" class="btn-page" style="text-decoration:none;">Sebelumnya</a>
                        <?php else: ?>
                            <button class="btn-page" disabled>Sebelumnya</button>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        if ($startPage > 1) {
                            echo '<a href="?page=1" class="btn-page" style="text-decoration:none;">1</a>';
                            if ($startPage > 2) echo '<button class="btn-page" disabled>...</button>';
                        }
                        
                        for ($i = $startPage; $i <= $endPage; $i++) {
                            $activeClass = $i === $currentPage ? 'active' : '';
                            echo '<a href="?page='.$i.'" class="btn-page '.$activeClass.'" style="text-decoration:none;">'.$i.'</a>';
                        }
                        
                        if ($endPage < $totalPages) {
                            if ($endPage < $totalPages - 1) echo '<button class="btn-page" disabled>...</button>';
                            echo '<a href="?page='.$totalPages.'" class="btn-page" style="text-decoration:none;">'.$totalPages.'</a>';
                        }
                        ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?>" class="btn-page" style="text-decoration:none;">Selanjutnya</a>
                        <?php else: ?>
                            <button class="btn-page" disabled>Selanjutnya</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Adding Log -->
    <div class="modal-overlay" id="addLogModal">
        <div class="modal-content" style="max-width: 800px; max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-header">
                <h2>Catat Data Log Baru</h2>
                <button class="close-btn" id="closeModalBtn">&times;</button>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <form id="logForm">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="rowIndex" id="formRowIndex" value="">
                    <input type="hidden" name="sheetName" id="formSheetName" value="">
                    <input type="hidden" name="category" id="formCategory" value="<?php echo htmlspecialchars($activeCat); ?>">
                    <!-- Informasi Umum -->
                    <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary);">Informasi Umum</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="Tanggal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Jam</label>
                            <select name="Jam" class="form-control" style="appearance: auto; cursor: pointer; padding-right: 1.5rem;" required>
                                <option value="" disabled selected>-- Pilih Shift Jam --</option>
                                <option value="Jam 08:00">Jam 08:00</option>
                                <option value="Jam 20:00">Jam 20:00</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Engineering</label>
                            <input type="text" name="NAMA ENGINERING" class="form-control" required>
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

                    <!-- Data PLN -->
                    <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary);">Data PLN</h3>
                    <div class="form-row">
                        <div class="form-group"><label>LWBP (PLN)</label><input type="text" name="LWBP (PLN)" class="form-control"></div>
                        <div class="form-group"><label>WBP (PLN)</label><input type="text" name="WBP (PLN)" class="form-control"></div>
                        <div class="form-group"><label>Total LWBP+WBP</label><input type="text" name="Totoal LWBP+WBP" class="form-control"></div>
                        <div class="form-group"><label>KVARH (PLN)</label><input type="text" name="KVARH (PLN)" class="form-control"></div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

                    <!-- KWH PUTR -->
                    <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary);">Data KWH PUTR</h3>
                    <div class="form-row">
                        <div class="form-group"><label>PH 1</label><input type="text" name="KWH PUTR PH1" class="form-control"></div>
                        <div class="form-group"><label>PH 2</label><input type="text" name="KWH PUTR PH2" class="form-control"></div>
                        <div class="form-group"><label>PH 3</label><input type="text" name="KWH PUTR PH3" class="form-control"></div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

                    <!-- KWH GENSET -->
                    <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary);">Data KWH GENSET</h3>
                    <div class="form-row">
                        <div class="form-group"><label>Genset 1 (Tower D)</label><input type="text" name="KWH GENSET 1 (TOWER D)" class="form-control"></div>
                        <div class="form-group"><label>Genset 2 (Tower F)</label><input type="text" name="KWH GENSET 2 (TOWER F)" class="form-control"></div>
                        <div class="form-group"><label>Genset 3 (Tower J)</label><input type="text" name="KWH GENSET 3 (TOWER J)" class="form-control"></div>
                        <div class="form-group"><label>Genset 4 (Tower N)</label><input type="text" name="KWH GENSET 4 (TOWER N)" class="form-control"></div>
                        <div class="form-group"><label>Genset 5 (Tower S)</label><input type="text" name="KWH GENSET 5 (TOWER S)" class="form-control"></div>
                        <div class="form-group"><label>Genset 6 (Tower X)</label><input type="text" name="KWH GENSET 6 (TOWER X)" class="form-control"></div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">
                    
                    <!-- UTILITY -->
                    <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary);">Data WTP & FWT</h3>
                    <div class="form-row">
                        <div class="form-group"><label>FWT</label><input type="text" name="FWT" class="form-control"></div>
                        <div class="form-group"><label>WTP 1 (AU/09)</label><input type="text" name="WTP 1 (AU/09)" class="form-control"></div>
                        <div class="form-group"><label>WTP 2 (AU/09A)</label><input type="text" name="WTP 2 (AU/09A)" class="form-control"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="cancelModalBtn">Batal</button>
                <button class="btn-primary" id="saveLogBtn">Kirim ke Google Sheet</button>
            </div>
        </div>
    </div>

    <!-- Modal Detail Log -->
    <div class="modal-overlay" id="detailLogModal">
        <div class="modal-content" style="max-width: 600px; max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-header">
                <h2>Detail Rekaman Log</h2>
                <button class="close-btn" id="closeDetailModalBtn">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody" style="overflow-y: auto; padding: 1rem;">
                <!-- Content injected here -->
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="closeDetailBtn">Tutup Detail</button>
            </div>
        </div>
    </div>

    <!-- Global Loading Overlay -->
    <div class="global-loader" id="globalLoader">
        <div class="spinner"></div>
        <p id="loaderText">Memproses data...</p>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="script.js"></script>
</body>
</html>
