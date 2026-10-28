<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - OneMovies Admin</title>
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --bg-body: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --border-color: #334155;
            --primary: #ff8d1b;
            --primary-hover: #e0760b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --success: #10b981;
            --info: #0ea5e9;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
        }

        .sidebar-brand h1 {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .sidebar-brand span {
            color: var(--primary);
        }

        .sidebar-brand .badge-admin {
            background: rgba(255, 141, 27, 0.2);
            color: var(--primary);
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-header {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 10px 12px 6px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .sidebar-menu li a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-menu li a:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.06);
        }

        .sidebar-menu li a.active {
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            box-shadow: 0 4px 12px rgba(255, 141, 27, 0.35);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            background: rgba(15, 23, 42, 0.4);
        }

        .admin-user-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 16px;
        }

        .admin-info {
            flex-grow: 1;
            overflow: hidden;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-role {
            font-size: 11px;
            color: var(--primary);
            font-weight: 500;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-navbar {
            background-color: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-color);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .page-title h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .page-title p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #334155;
            color: #f8fafc;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: #ef4444;
            color: #fff;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .content-container {
            padding: 32px;
            flex-grow: 1;
        }

        /* Flash Notifications */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-close {
            background: none;
            border: none;
            color: currentColor;
            cursor: pointer;
            font-size: 16px;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Cards & Tables */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 24px;
        }

        /* Data Tables */
        .table-responsive {
            overflow-x: auto;
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        table.admin-table th {
            background-color: rgba(15, 23, 42, 0.6);
            color: var(--text-muted);
            padding: 14px 18px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        table.admin-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        table.admin-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        table.admin-table tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-genre {
            background: rgba(255, 141, 27, 0.15);
            color: var(--primary);
            border: 1px solid rgba(255, 141, 27, 0.3);
        }

        .badge-year {
            background: #334155;
            color: #cbd5e1;
        }

        .badge-rating {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-role-admin {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .badge-role-user {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
        }

        /* Search & Filter Forms */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input-wrap i {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-control {
            background-color: #0f172a;
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 9px 14px 9px 38px;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 141, 27, 0.25);
        }

        select.form-control {
            padding-left: 14px;
            cursor: pointer;
        }

        /* Forms Styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #f1f5f9;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input {
            width: 100%;
            background-color: #0f172a;
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 141, 27, 0.25);
        }

        textarea.form-input {
            resize: vertical;
            min-height: 100px;
        }

        .form-helper {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .invalid-feedback {
            color: #f87171;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
        }

        .pagination li a, .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: 8px;
            background: #0f172a;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination li a:hover {
            color: #fff;
            border-color: var(--primary);
        }

        .pagination li.active span {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            font-weight: 600;
        }

        .pagination li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.orange {
            background: rgba(255, 141, 27, 0.15);
            color: var(--primary);
        }

        .stat-icon.blue {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .stat-icon.green {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .stat-icon.purple {
            background: rgba(168, 85, 247, 0.15);
            color: #a855f7;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Delete Confirmation Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            width: 100%;
            max-width: 440px;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-icon-danger {
            width: 60px;
            height: 60px;
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 16px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .modal-desc {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .thumbnail-img {
            width: 46px;
            height: 64px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="fa-solid fa-clapperboard" style="color: var(--primary); font-size: 24px;"></i>
            <div>
                <h1>One<span>Movies</span></h1>
            </div>
            <span class="badge-admin">Admin</span>
        </a>

        <ul class="sidebar-menu">
            <div class="menu-header">Menu Utama</div>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.films.index') }}" class="{{ request()->routeIs('admin.films.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-film"></i>
                    <span>Kelola Film</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.casts.index') }}" class="{{ request()->routeIs('admin.casts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-viewfinder"></i>
                    <span>Cast & Aktor</span>
                </a>
            </li>

            <div class="menu-header">Interaksi & User</div>
            <li>
                <a href="{{ route('admin.comments.index') }}" class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i>
                    <span>Komentar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Kelola User</span>
                </a>
            </li>

            <div class="menu-header">Aksi Cepat</div>
            <li>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Lihat Website</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" style="color: #ef4444;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar (Logout)</span>
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="admin-user-card">
                <div class="admin-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="admin-info">
                    <div class="admin-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <div class="admin-role"><i class="fa-solid fa-shield-halved"></i> Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="page-title">
                <h2>@yield('header_title', 'Dashboard')</h2>
                <p>@yield('header_subtitle', 'Selamat datang di panel admin OneMovies.')</p>
            </div>
            <div class="navbar-actions">
                @yield('header_actions')
                <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" target="_blank">
                    <i class="fa-solid fa-globe"></i> Buka Website
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="content-container">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success">
                    <div class="alert-content">
                        <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <div class="alert-content">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-content">
                        <i class="fa-solid fa-circle-xmark" style="font-size: 18px;"></i>
                        <div>
                            <strong>Terjadi kesalahan input:</strong>
                            <ul style="margin: 4px 0 0 16px;">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Universal Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteConfirmModal">
        <div class="modal-box">
            <div class="modal-icon-danger">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 class="modal-title" id="deleteModalTitle">Konfirmasi Hapus</h3>
            <p class="modal-desc" id="deleteModalDesc">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="background:#ef4444; color:#fff;">
                        <i class="fa-solid fa-trash"></i> Ya, Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(deleteUrl, itemTitle) {
            const modal = document.getElementById('deleteConfirmModal');
            const form = document.getElementById('deleteForm');
            const desc = document.getElementById('deleteModalDesc');
            
            form.action = deleteUrl;
            if (itemTitle) {
                desc.textContent = `Apakah Anda yakin ingin menghapus "${itemTitle}"? Semua data terkait akan ikut terhapus.`;
            } else {
                desc.textContent = 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            }
            modal.classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteConfirmModal').classList.remove('show');
        }

        // Close modal when clicking outside
        document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
