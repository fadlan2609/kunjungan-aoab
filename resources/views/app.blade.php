<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>BPRS Amanah Bangsa - Sistem Kunjungan Nasabah</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
    
    <link rel="icon" type="image/png" href="{{ asset('images/photo1.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/photo1.png') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .login-page {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .login-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .login-tagline {
            color: #666;
            font-size: 14px;
        }

        .login-form-group {
            margin-bottom: 20px;
        }

        .login-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .login-form-group label i {
            margin-right: 8px;
            color: #667eea;
        }

        .login-form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .login-form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .login-password-wrapper {
            position: relative;
        }

        .login-password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
        }

        .login-message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            display: none;
        }

        .login-message.error {
            background: #fee;
            color: #c33;
            display: block;
        }

        .login-message.success {
            background: #d4edda;
            color: #155724;
            display: block;
        }

        .login-hint {
            margin-top: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .login-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 20px;
            margin: 0;
            color: #333;
        }

        .tagline {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .user-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .header-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            align-items: center;
        }

        .stat-item {
            text-align: center;
            min-width: 70px;
        }

        .stat-item i {
            font-size: 22px;
            color: #667eea;
            margin-bottom: 5px;
            display: block;
        }

        .stat-item span {
            display: block;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            line-height: 1.2;
        }

        .stat-item small {
            font-size: 11px;
            color: #666;
            display: block;
        }

        .stat-item i.fa-clock {
            color: #ffc107;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .form-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-section h2 {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
            font-size: 13px;
        }

        .form-group label i {
            margin-right: 6px;
            color: #667eea;
            width: 18px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group input:disabled,
        .form-group select:disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
        }

        .btn-success {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 11px;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 11px;
        }

        .table-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .table-section h2 {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 12px;
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-wrapper {
            flex: 1;
            position: relative;
            min-width: 200px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .search-wrapper input {
            width: 100%;
            padding: 8px 10px 8px 35px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
        }

        .filter-date-container {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .filter-date-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-date-group label {
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin: 0;
        }

        .filter-date-group input {
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
        }

        .btn-filter {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-clear-filter {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1000px;
        }

        th, td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        th {
           background: #f8f9fa;
            font-weight: 600;
            position: sticky;
            top: 0;
            white-space: nowrap;
            text-align: center !important;
            vertical-align: middle !important;
        }

        td {
            word-break: break-word;
            vertical-align: middle;
        }
        
        td.text-center, 
        th.text-center {
            text-align: center !important;
        }
        
        /* Kolom aksi */
        th:last-child,
        td:last-child {
            text-align: center !important;
}

        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            white-space: nowrap;
        }

        .action-btn i {
            font-size: 11px;
        }

        .action-btn.approve {
            background: #28a745;
            color: white;
        }

        .action-btn.approve:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .action-btn.reject {
            background: #dc3545;
            color: white;
        }

        .action-btn.reject:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .action-btn.cancel {
            background: #ffc107;
            color: #333;
        }

        .action-btn.cancel:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .action-btn.edit {
            background: #ffc107;
            color: #333;
        }

        .action-btn.edit:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .action-btn.delete {
            background: #dc3545;
            color: white;
        }

        .action-btn.delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-pending {
            background: #ffc107;
            color: #333;
        }

        .status-approved {
            background: #28a745;
            color: white;
        }

        .status-rejected {
            background: #dc3545;
            color: white;
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 10px;
            white-space: nowrap;
        }

        .info-badge.warning {
            background: #ffebee;
            color: #c62828;
        }

        .info-badge.success {
            background: #c8e6c9;
            color: #2e7d32;
        }

        .clickable-text {
            cursor: pointer;
            color: #667eea;
            text-decoration: underline;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
        
        .clickable-text:hover {
            color: #764ba2;
        }
        
        .catatan-badge {
            cursor: pointer;
            background: #ffebee;
            padding: 4px 8px;
            border-radius: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
            display: inline-block;
            font-size: 10px;
            color: #c62828;
        }
        
        .catatan-badge:hover {
            background: #ffcdd2;
        }

        .detail-modal, .password-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .detail-modal.show, .password-modal.show {
            display: flex;
        }

        .detail-modal-content, .password-modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            animation: slideUp 0.3s ease;
        }

        .detail-modal-header, .password-modal-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            position: relative;
        }

        .detail-modal-close, .password-modal-close {
            position: absolute;
            right: 15px;
            top: 15px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #999;
        }

        .detail-modal-body, .password-modal-body {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .password-modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .password-icon {
            width: 50px;
            height: 50px;
            background: #fee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .password-icon i {
            font-size: 24px;
            color: #dc3545;
        }

        .password-input-wrapper {
            position: relative;
            margin-top: 15px;
        }

        .password-input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .password-input {
            width: 100%;
            padding: 10px 40px 10px 35px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
        }

        .password-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        .password-btn-primary {
            background: #007bff;
            color: white;
        }

        .password-btn-secondary {
            background: #6c757d;
            color: white;
        }

        .loading-indicator {
            text-align: center;
            padding: 10px;
            color: #667eea;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 8px;
            display: none;
            font-size: 13px;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            display: block;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            display: block;
        }

        .message.info {
            background: #cce5ff;
            color: #004085;
            display: block;
        }

        .file-info {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .current-photo {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .current-photo img {
            max-width: 80px;
            border-radius: 5px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            transition: all 0.3s;
            margin-top: 5px;
        }

        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            display: none;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .export-dropdown {
            position: relative;
            display: inline-block;
        }

        .export-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            min-width: 180px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 8px;
            z-index: 100;
            margin-top: 5px;
        }

        .export-content button {
            width: 100%;
            text-align: left;
            border: none;
            background: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 14px;
        }

        .export-content button:hover {
            background: #f0f0f0;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            white-space: nowrap;
        }

        .role-badge i {
            font-size: 11px;
        }

        .role-badge.admin {
            background: #dc3545;
            color: white;
        }

        .role-badge.manager {
            background: #fd7e14;
            color: white;
        }

        .role-badge.ao {
            background: #28a745;
            color: white;
        }
        
        /* Style untuk foto thumbnail di tabel */
.table-container img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 5px;
    cursor: pointer;
    border: 1px solid #ddd;
    transition: transform 0.2s;
}

.table-container img:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

/* Notification Bell */
.notification-bell {
    position: relative;
    cursor: pointer;
    background: none;
    border: none;
    font-size: 20px;
    color: #666;
    transition: all 0.3s;
    padding: 5px 10px;
    border-radius: 50%;
}

.notification-bell:hover {
    background: #f0f0f0;
    color: #667eea;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 50%;
    min-width: 18px;
    text-align: center;
}

/* Notification Dropdown */
.notification-dropdown {
    position: absolute;
    top: 50px;
    right: 20px;
    width: 350px;
    max-height: 400px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    z-index: 1000;
    display: none;
    overflow: hidden;
}

.notification-dropdown.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-header {
    padding: 12px 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header button {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 16px;
}

.notification-list {
    max-height: 350px;
    overflow-y: auto;
}

.notification-item {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.2s;
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item.unread {
    background: #e8f4fd;
    border-left: 3px solid #667eea;
}

.notification-item .title {
    font-weight: 600;
    font-size: 13px;
    color: #333;
    margin-bottom: 3px;
}

.notification-item .message {
    font-size: 11px;
    color: #666;
    margin-bottom: 3px;
}

.notification-item .time {
    font-size: 10px;
    color: #999;
}

.notification-empty {
    padding: 30px;
    text-align: center;
    color: #999;
}

/* Toast Notification yang lebih menarik */
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: white;
    color: #333;
    padding: 0;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    z-index: 9999;
    animation: slideInRight 0.3s ease;
    display: none;
    min-width: 300px;
    overflow: hidden;
}

.toast-notification.show {
    display: block;
}

.toast-header {
    padding: 10px 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.toast-body {
    padding: 12px 15px;
    font-size: 13px;
}

.toast-body i {
    margin-right: 8px;
    color: #28a745;
}

.toast-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 14px;
}

/* Style untuk search box user */
#searchUserInput:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102,126,234,0.2);
}

#userTableInfo {
    font-size: 12px;
    color: #666;
    padding: 8px 0;
    border-top: 1px solid #eee;
}

/* Perbaikan tampilan table-footer dan pagination */
.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
    flex-wrap: wrap;
    gap: 10px;
}

.pagination-info {
    font-size: 13px;
    color: #666;
    background: #f8f9fa;
    padding: 6px 12px;
    border-radius: 6px;
}

.pagination {
    display: flex;
    gap: 8px;
    align-items: center;
}

.page-btn {
    background: #f8f9fa;
    border: 1px solid #ddd;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 13px;
}

.page-btn:hover:not(:disabled) {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

#pageInfo {
    font-size: 13px;
    color: #333;
    background: white;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
    min-width: 90px;
    text-align: center;
}

/* Preview foto sebelum upload */
.foto-preview-container {
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px dashed #667eea;
    display: none;
}

.foto-preview-container.show {
    display: block;
}

.foto-preview-img {
    max-width: 150px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #28a745;
}

.foto-preview-info {
    font-size: 11px;
    color: #666;
    margin-top: 5px;
}

.btn-remove-preview {
    background: #dc3545;
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 10px;
    margin-top: 5px;
}

/* ==================== LOADING ANIMATION ==================== */

/* Loading overlay untuk seluruh halaman */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(3px);
}

.loading-overlay.show {
    display: flex;
}

/* Loading spinner utama */
.loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Loading dengan teks */
.loading-card {
    background: white;
    border-radius: 12px;
    padding: 25px 35px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.loading-card .spinner {
    width: 50px;
    height: 50px;
    border: 3px solid #e0e0e0;
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 0.6s linear infinite;
    margin: 0 auto 15px;
}

.loading-card p {
    margin: 0;
    color: #333;
    font-size: 14px;
}

.loading-card .dots {
    display: inline-block;
    width: 20px;
    text-align: left;
}

/* Loading untuk tombol */
.btn-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.btn-loading i {
    animation: spin 0.8s linear infinite;
}

/* Loading untuk tabel */
.table-loading {
    position: relative;
    min-height: 200px;
}

.table-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Loading bar di atas halaman */
.loading-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
    z-index: 10000;
    transition: width 0.3s ease;
    box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
}

/* Skeleton loading untuk tabel */
.skeleton-row {
    display: flex;
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.skeleton-cell {
    height: 20px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 4px;
    margin: 0 5px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Loading spinner kecil untuk inline */
.spinner-sm {
    width: 16px;
    height: 16px;
    border: 2px solid #e0e0e0;
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 0.6s linear infinite;
    display: inline-block;
}

/* Loading untuk card */
.loading-card-simple {
    text-align: center;
    padding: 40px;
    color: #667eea;
}

.loading-card-simple i {
    font-size: 40px;
    margin-bottom: 15px;
    animation: spin 1s linear infinite;
}

/* Reminder Banner */
.reminder-banner {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: none;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    animation: slideDown 0.3s ease;
}

.reminder-banner.show {
    display: flex;
}

.reminder-banner .reminder-content {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.reminder-banner i {
    font-size: 24px;
    animation: bellShake 1s infinite;
}

@keyframes bellShake {
    0%, 100% { transform: rotate(0); }
    25% { transform: rotate(15deg); }
    75% { transform: rotate(-15deg); }
}

.reminder-banner .reminder-text {
    font-size: 14px;
}

.reminder-banner .reminder-count {
    background: white;
    color: #ee5a24;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 18px;
}

.reminder-banner .btn-view {
    background: white;
    color: #ee5a24;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

.reminder-banner .btn-view:hover {
    transform: scale(1.05);
}

/* Auto Save Indicator */
.auto-save-indicator {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background: #28a745;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    z-index: 999;
    display: none;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    animation: slideInLeft 0.3s ease;
}

.auto-save-indicator.show {
    display: flex;
}

.auto-save-indicator i {
    font-size: 12px;
}

@keyframes slideInLeft {
    from { transform: translateX(-100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.draft-badge {
    background: #ffc107;
    color: #333;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    margin-left: 10px;
}

.load-draft-btn {
    background: #17a2b8;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 11px;
    margin-left: 10px;
}

/* Style untuk tombol Word */
.btn-word {
    background: #2b5797;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.3s;
}

.btn-word:hover {
    background: #1e3e6e;
    transform: translateY(-1px);
}

/* Dashboard Styles */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.dashboard-card {
    background: white;
    border-radius: 15px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.dashboard-card:hover {
    transform: translateY(-3px);
}

.dashboard-card .card-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 12px;
}

.dashboard-card .card-value {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.dashboard-card .card-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.dashboard-card .card-trend {
    font-size: 11px;
    margin-top: 8px;
}

.trend-up { color: #28a745; }
.trend-down { color: #dc3545; }

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
    border-left: 4px solid #667eea;
    padding-left: 12px;
}

.chart-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .chart-grid {
        grid-template-columns: 1fr;
    }
    .dashboard-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    .table-footer {
        flex-direction: column;
        text-align: center;
    }
    
    .pagination-info {
        order: 2;
    }
    
    .pagination {
        order: 1;
    }
}

        @media (max-width: 768px) {
            .container { padding: 12px; }
            .header { flex-direction: column; text-align: center; }
            .logo-container { justify-content: center; }
            .header-stats { justify-content: center; width: 100%; gap: 15px; }
            .stat-item { min-width: 60px; }
            .stat-item span { font-size: 18px; }
            .stat-item i { font-size: 18px; }
            .user-info { justify-content: center; width: 100%; }
            .form-section, .table-section { padding: 15px; }
            .form-row { grid-template-columns: 1fr; gap: 10px; }
            .search-box { flex-direction: column; }
            .search-wrapper { width: 100%; }
            .search-box button { width: 100%; }
            .filter-date-container { flex-direction: column; align-items: stretch; }
            .filter-date-group { justify-content: space-between; }
            .table-footer { flex-direction: column; text-align: center; }
            .action-buttons { flex-direction: column; align-items: stretch; }
            .action-btn { justify-content: center; }
        }

        @media (max-width: 480px) {
            .stat-item { min-width: 55px; }
            .stat-item span { font-size: 16px; }
            .stat-item i { font-size: 16px; }
            .stat-item small { font-size: 9px; }
        }
        
        /* Untuk tablet landscape */
        @media (min-width: 769px) and (max-width: 1024px) {
            .stat-item { min-width: 70px; }
            .stat-item span { font-size: 20px; }
            .table-container { overflow-x: auto; }
        }
        
        /* Untuk mobile dengan orientasi landscape */
        @media (max-width: 768px) and (orientation: landscape) {
            .header-stats { gap: 10px; }
            .stat-item { min-width: 50px; }
            .stat-item span { font-size: 14px; }
        }
        
    </style>
</head>
<body>
    <!-- Login Page -->
    <div id="loginPage" class="login-page">
        <div class="login-container">
            <div class="login-header">
                <img src="{{ asset('images/photo1.png') }}" alt="BPRS Amanah Bangsa" class="login-logo">
                <h2>BPRS Amanah Bangsa</h2>
                <p class="login-tagline">Sistem Kunjungan Nasabah</p>
            </div>
            
            <form id="loginForm">
                <div class="login-form-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="loginUsername" placeholder="Masukkan username" required autocomplete="off">
                </div>
                
                <div class="login-form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="login-password-wrapper">
                        <input type="password" id="loginPassword" placeholder="Masukkan password" required autocomplete="off">
                        <button type="button" class="login-password-toggle" onclick="toggleLoginPassword()">
                            <i class="fas fa-eye" id="loginToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div id="loginMessage" class="login-message"></div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>
            
            <div class="login-hint">
                <p><i class="fas fa-info-circle"></i> Gunakan akun yang diberikan oleh admin</p>
            </div>
            
            <div class="login-footer">
                <p>&copy; 2026 BPRS Amanah Bangsa. All rights reserved.</p>
                <p>Versi 1.2</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="container" style="display: none;">
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/photo1.png') }}" alt="Logo" class="logo">
                <div class="brand-text">
                    <h1>BPRS Amanah Bangsa</h1>
                    <p class="tagline">Amanah, Professional, dan Terpercaya</p>
                </div>
            </div>
            <div class="user-info">
                <div class="user-badge" id="userBadge">
                    <i class="fas fa-user-circle"></i> <span id="userNameDisplay">Administrator</span>
                    <span id="userRoleBadge"></span>
                </div>
                <button id="btnManageUsers" onclick="openUserModal()" class="btn-primary" style="background: #28a745; padding: 8px 16px; display: none;">
                    <i class="fas fa-users-cog"></i> Kelola User
                </button>
                <button onclick="logout()" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
                <!-- Notification Bell -->
                <button class="notification-bell" id="notificationBell" onclick="toggleNotificationDropdown()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <span><i class="fas fa-bell"></i> Notifikasi</span>
                        <button onclick="markAllAsRead()"><i class="fas fa-check-double"></i></button>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="notification-empty">Memuat notifikasi...</div>
                    </div>
                </div>
                <button id="btnLogActivity" onclick="openLogModal()" class="btn-info" style="padding: 8px 16px; display: none;">
                    <i class="fas fa-history"></i> Log Activity
                </button>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <i class="fas fa-building"></i>
                    <span id="totalCabang">0</span>
                    <small>Cabang</small>
                </div>
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <span id="totalKunjungan">0</span>
                    <small>Kunjungan</small>
                </div>
                <div class="stat-item">
                    <i class="fas fa-calendar"></i>
                    <span id="hariIni">0</span>
                    <small>Hari Ini</small>
                </div>
                <div class="stat-item" id="statPending">
                    <i class="fas fa-clock"></i>
                    <span id="pendingCount">0</span>
                    <small>Pending</small>
                </div>
            </div>
        </div>

        <!-- DASHBOARD SECTION -->
<!-- DASHBOARD SECTION -->
<div id="dashboardSection" class="form-section" style="display: none;">
    <h2><i class="fas fa-chart-line"></i> Dashboard Statistik</h2>
    
    <!-- ========== FILTER BOX ========== -->
    <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <!-- Filter Cabang -->
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #555;">
                    <i class="fas fa-building"></i> Cabang
                </label>
                <select id="dashboardFilterCabang" style="width: 100%; padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 13px;">
                    <option value="all">Semua Cabang</option>
                    <option value="Pusat">Pusat</option>
                    <option value="Kisaran">Kisaran</option>
                    <option value="Perdagangan">Perdagangan</option>
                    <option value="Pematangsiantar">Pematangsiantar</option>
                    <option value="Sidamanik">Sidamanik</option>
                    <option value="Stabat">Stabat</option>
                </select>
            </div>
            
            <!-- Filter AO -->
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #555;">
                    <i class="fas fa-user-tie"></i> Account Officer
                </label>
                <select id="dashboardFilterAO" style="width: 100%; padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 13px;">
                    <option value="all">Semua AO</option>
                </select>
            </div>
            
            <!-- Filter Status -->
            <div style="flex: 1; min-width: 140px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #555;">
                    <i class="fas fa-chart-simple"></i> Status
                </label>
                <select id="dashboardFilterStatus" style="width: 100%; padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 13px;">
                    <option value="all">Semua Status</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="approved">✅ Disetujui</option>
                    <option value="rejected">❌ Ditolak</option>
                </select>
            </div>
            
            <!-- Filter Tanggal Mulai -->
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #555;">
                    <i class="fas fa-calendar-alt"></i> Dari Tanggal
                </label>
                <input type="date" id="dashboardFilterStartDate" style="width: 100%; padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 13px;">
            </div>
            
            <!-- Filter Tanggal Sampai -->
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #555;">
                    <i class="fas fa-calendar-alt"></i> Sampai Tanggal
                </label>
                <input type="date" id="dashboardFilterEndDate" style="width: 100%; padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 13px;">
            </div>
            
            <!-- Tombol Aksi -->
            <div style="display: flex; gap: 8px;">
                <button id="dashboardApplyFilter" class="btn-primary" style="padding: 8px 16px;">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
                <button id="dashboardResetFilter" class="btn-secondary" style="padding: 8px 16px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
        
        <!-- Quick Date Buttons -->
        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 15px; padding-top: 10px; border-top: 1px solid #e0e0e0;">
            <span style="font-size: 12px; color: #666; align-self: center;"><i class="fas fa-bolt"></i> Cepat:</span>
            <button type="button" class="btn-info" onclick="setQuickDate('today')" style="padding: 5px 12px; font-size: 11px;">
                <i class="fas fa-sun"></i> Hari Ini
            </button>
            <button type="button" class="btn-info" onclick="setQuickDate('yesterday')" style="padding: 5px 12px; font-size: 11px;">
                <i class="fas fa-calendar-day"></i> Kemarin
            </button>
            <button type="button" class="btn-info" onclick="setQuickDate('week')" style="padding: 5px 12px; font-size: 11px;">
                <i class="fas fa-calendar-week"></i> Minggu Ini
            </button>
            <button type="button" class="btn-info" onclick="setQuickDate('month')" style="padding: 5px 12px; font-size: 11px;">
                <i class="fas fa-calendar-alt"></i> Bulan Ini
            </button>
            <button type="button" class="btn-info" onclick="setQuickDate('lastMonth')" style="padding: 5px 12px; font-size: 11px;">
                <i class="fas fa-calendar-minus"></i> Bulan Lalu
            </button>
            <button type="button" class="btn-info" onclick="setQuickDate('year')" style="padding: 5px 12px; font-size: 11px;">
                <i class="fas fa-calendar-year"></i> Tahun Ini
            </button>
        </div>
        
        <!-- Info Filter Aktif (HANYA SEKALI) -->
        <div id="dashboardFilterInfo" style="margin-top: 10px; font-size: 11px; color: #667eea; display: none;">
            <i class="fas fa-info-circle"></i> <span id="dashboardFilterInfoText"></span>
            <button onclick="resetDashboardFilters()" style="background: none; border: none; color: #dc3545; cursor: pointer; margin-left: 10px;">
                <i class="fas fa-times"></i> Hapus filter
            </button>
        </div>
    </div>
    <!-- ========== END FILTER BOX ========== -->
    
    <!-- Summary Cards -->
    <div class="dashboard-cards" id="summaryCards">
        <!-- Akan diisi JS -->
    </div>
    
    <!-- Charts Grid -->
    <div class="chart-grid">
        <div class="chart-container">
            <div class="chart-title"><i class="fas fa-chart-bar"></i> Tren Kunjungan per Bulan</div>
            <canvas id="monthlyChart" style="max-height: 300px;"></canvas>
        </div>
        
        <div class="chart-container">
            <div class="chart-title"><i class="fas fa-chart-pie"></i> Distribusi Status</div>
            <canvas id="statusChart" style="max-height: 300px;"></canvas>
        </div>
        
        <div class="chart-container">
            <div class="chart-title"><i class="fas fa-trophy"></i> Top 5 AO</div>
            <canvas id="topAOChart" style="max-height: 300px;"></canvas>
        </div>
        
        <div class="chart-container">
            <div class="chart-title"><i class="fas fa-chart-line"></i> Tren Harian (7 Hari)</div>
            <canvas id="dailyChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
    
    <!-- Cabang Stats Table -->
    <div class="chart-container">
        <div class="chart-title"><i class="fas fa-building"></i> Statistik per Cabang</div>
        <div class="table-container">
            <table class="table" id="cabangStatsTable">
                <thead>
                    <tr><th>Cabang</th><th>Jumlah Kunjungan</th><th>Persentase</th></tr>
                </thead>
                <tbody id="cabangStatsBody">
                    <tr><td colspan="3" class="text-center">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
        
        <!-- Form untuk AO -->
        <div class="form-section" id="formSection" style="display: none;">
            <h2 id="formTitle"><i class="fas fa-plus-circle"></i> Tambah Data Kunjungan Baru</h2>
            <form id="kunjunganForm" enctype="multipart/form-data">
                <input type="hidden" id="rowId" value="">
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-store"></i> Nama Kantor *</label>
                        <select id="namaCabang" required>
                            <option value="Pusat">Pusat</option>
                            <option value="Kisaran">Kisaran</option>
                            <option value="Perdagangan">Perdagangan</option>
                            <option value="Pematangsiantar">Pematangsiantar</option>
                            <option value="Sidamanik">Sidamanik</option>
                            <option value="Stabat">Stabat</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Nama AO *</label>
                        <input type="text" id="namaAO" placeholder="Nama Account Officer" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nama Nasabah *</label>
                        <input type="text" id="namaNasabah" placeholder="Nama lengkap nasabah" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> No Pembiayaan *</label>
                        <input type="text" id="noPembiayaan" placeholder="Contoh: 1234567890" pattern="[0-9]+" title="Hanya angka" onkeypress="return hanyaAngka(event)" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Alamat *</label>
                    <textarea id="alamat" rows="2" placeholder="Alamat lengkap nasabah" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Tanggal Kunjungan *</label>
                        <input type="date" id="tanggalKunjungan" required>
                    </div>
                    
                    <div class="form-group">
    <label><i class="fas fa-clipboard-list"></i> Keterangan</label>
    <textarea id="keterangan" rows="3" placeholder="Contoh: Survey, Pengajuan, dll" style="resize: vertical;"></textarea>
</div>
                </div>
                
                <!-- TAMBAHKAN INI SETELAHNYA -->
<div class="form-row">
    <div class="form-group">
    <label><i class="fas fa-chart-line"></i> Hasil Kunjungan *</label>
    <select id="hasilKunjungan" name="hasil_kunjungan" required class="form-control">
        <option value="">-- Pilih Hasil Kunjungan --</option>
        <option value="Nasabah Ditemui">✅ Nasabah Ditemui</option>
        <option value="Nasabah Tidak Ditemui">❌ Nasabah Tidak Ditemui</option>
        <option value="Angsuran Lancar">💰 Angsuran Lancar</option>
        <option value="Angsuran Bermasalah">⚠️ Angsuran Bermasalah</option>
        <option value="Restrukturisasi">🤝 Restrukturisasi</option>
        <option value="Monitoring">📊 Monitoring</option>
        <option value="Lainnya">📝 Lainnya (Isi Manual)</option>
    </select>
    <div class="file-info">
        <i class="fas fa-info-circle"></i> Pilih hasil dari kunjungan yang dilakukan
    </div>
</div>

<!-- Input untuk opsi Lainnya (hidden by default) -->
<div id="hasilLainnyaGroup" style="display: none; margin-top: 10px;">
    <div class="form-group">
        <label><i class="fas fa-pencil-alt"></i> Tulis Hasil Kunjungan (Lainnya)</label>
        <input type="text" id="hasilKunjunganLainnya" class="form-control" 
               placeholder="Contoh: Kunjungan baik, nasabah kooperatif">
        <div class="file-info">
            <i class="fas fa-info-circle"></i> Silakan tulis hasil kunjungan secara lengkap
        </div>
    </div>
</div>
</div>
                
                <div class="form-group">
                    <label><i class="fas fa-camera"></i> Foto Bukti</label>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                    <div class="file-info">
                        <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG | Max: 5MB
                    </div>
                    
                    <!-- TAMBAHKAN INI - CONTAINER PREVIEW FOTO -->
    <div id="fotoPreviewContainer" class="foto-preview-container">
        <img id="fotoPreviewImg" class="foto-preview-img" src="" alt="Preview">
        <div class="foto-preview-info">
            <i class="fas fa-image"></i> <span id="fotoPreviewName"></span>
            <span id="fotoPreviewSize"></span>
        </div>
        <button type="button" class="btn-remove-preview" onclick="removeFotoPreview()">
            <i class="fas fa-trash"></i> Hapus
        </button>
    </div>
    <!-- ========== SAMPAI SINI ========== -->
                    
                    <div class="alert alert-warning" style="font-size: 11px; padding: 8px 12px; margin-top: 8px; border-radius: 6px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
        <i class="fas fa-map-marker-alt"></i> <strong>Perhatian!</strong> Foto harus menggunakan kamera GPS/Maps yang menampilkan lokasi dan timestamp
    </div>
                    <div id="currentPhoto" class="current-photo" style="display: none;">
                        <small>Foto saat ini:</small><br>
                        <img id="currentPhotoImg" src="" alt="Current Photo">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" id="btnSubmit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <button type="button" id="btnCancel" class="btn-secondary" style="display: none;">
                        <i class="fas fa-times"></i> Batal Edit
                    </button>
                </div>
                
                <div id="loadingIndicator" class="loading-indicator" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Memproses data...
                </div>
                
                <div id="message" class="message"></div>
            </form>
        </div>
        
        <div class="table-section">
            <h2><i class="fas fa-table"></i> Data Kunjungan AO</h2>
            
            <div class="filter-date-container">
                <div class="filter-date-group">
                    <label><i class="fas fa-calendar-alt"></i> Dari Tanggal:</label>
                    <input type="date" id="filterStartDate">
                </div>
                <div class="filter-date-group">
                    <label><i class="fas fa-calendar-alt"></i> Sampai Tanggal:</label>
                    <input type="date" id="filterEndDate">
                </div>
                <button class="btn-filter" onclick="filterByDate()">
                    <i class="fas fa-filter"></i> Filter Tanggal
                </button>

                <!-- Di dalam div filter-date-container, setelah filter end date -->
<div class="filter-date-group">
    <label><i class="fas fa-chart-simple"></i> Status:</label>
    <select id="filterStatus" style="padding: 8px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 13px;">
        <option value="all">Semua Status</option>
        <option value="pending">⏳ Pending</option>
        <option value="approved">✅ Disetujui</option>
        <option value="rejected">❌ Ditolak</option>
    </select>
</div>

                <button class="btn-clear-filter" onclick="clearAllFilters()">
    <i class="fas fa-times"></i> Hapus Semua Filter
</button>
            </div>
            
            <div class="search-box">
    <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari berdasarkan Nama AO / Cabang / Nasabah / No Pembiayaan...">
    </div>
    <button id="searchButton" class="btn-primary"><i class="fas fa-search"></i> Cari</button>
    <button id="resetButton" class="btn-secondary"><i class="fas fa-sync-alt"></i> Reset</button>
    <button id="refreshButton" class="btn-info"><i class="fas fa-sync-alt"></i> Refresh</button>
    
    <!-- ========== TOMBOL EXPORT SELECTED (TAMBAHKAN INI) ========== -->
    <div class="export-selected-dropdown" style="position: relative; display: inline-block;">
    <button id="exportSelectedBtn" class="btn-success" style="background: #17a2b8;" onclick="toggleExportSelectedDropdown()">
        <i class="fas fa-check-double"></i> Export Selected ▼
    </button>
    <div id="exportSelectedDropdown" class="export-content" style="display: none; position: absolute; right: 0; top: 100%; background: white; min-width: 180px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); border-radius: 8px; z-index: 100; margin-top: 5px;">
        <button onclick="exportSelectedToExcel(getSelectedData())"><i class="fas fa-file-excel"></i> Export ke Excel</button>
        <button onclick="exportSelectedToPDF(getSelectedData())"><i class="fas fa-file-pdf"></i> Export ke PDF</button>
        <button onclick="exportSelectedToWord()"><i class="fas fa-file-word" style="color: #2b5797;"></i> Export ke Word</button>
    </div>
</div>
    <!-- ========== SAMPAI SINI ========== -->
    
    <div class="export-dropdown">
        <button id="exportButton" class="btn-success"><i class="fas fa-download"></i> Export All ▼</button>
        <div id="exportDropdown" class="export-content">
            <button onclick="exportToExcel()"><i class="fas fa-file-excel"></i> Export ke Excel</button>
            <button onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Export ke PDF</button>
            <button onclick="exportToWord()"><i class="fas fa-file-word" style="color: #2b5797;"></i> Export ke Word</button>
        </div>
    </div>
</div>
            
            <div class="table-container">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th style="text-align: center; vertical-align: middle; width: 30px;">
            <input type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll()" style="width: 16px; height: 16px; cursor: pointer;">
        </th>
                            <th style="text-align: center; vertical-align: middle;">No</th>
                            <th style="text-align: center; vertical-align: middle;">Cabang</th>
                            <th style="text-align: center; vertical-align: middle;">Nama AO</th>
                            <th style="text-align: center; vertical-align: middle;">Nasabah</th>
                            <th style="text-align: center; vertical-align: middle;">No Pembiayaan</th>
                            <th style="text-align: center; vertical-align: middle;">Alamat</th>
                            <th style="text-align: center; vertical-align: middle;">Tgl Kunjungan</th>
                            <th style="text-align: center; vertical-align: middle;">Keterangan</th>
                            <th style="text-align: center; vertical-align: middle;">Hasil Kunjungan</th>
                            <th style="text-align: center; vertical-align: middle;">Waktu Input</th>
                            <th style="text-align: center; vertical-align: middle;">Foto</th>
                            <th style="text-align: center; vertical-align: middle;">Status</th>
                            <th style="text-align: center; vertical-align: middle;">Catatan</th>
                            <th style="text-align: center; vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="12" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...<\/td></tr>
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="pagination-info">Menampilkan <span id="startRecord">0</span> - <span id="endRecord">0</span> dari <span id="totalRecords">0</span> data</div>
                <div class="pagination">
                    <button class="page-btn" id="prevPage" disabled><i class="fas fa-chevron-left"></i></button>
                    <span id="pageInfo">Halaman 1</span>
                    <button class="page-btn" id="nextPage" disabled><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Text -->
    <div id="detailModal" class="detail-modal">
        <div class="detail-modal-content">
            <div class="detail-modal-header">
                <h3 id="detailModalTitle">Detail</h3>
                <button class="detail-modal-close" onclick="closeDetailModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="detail-modal-body" id="detailModalBody"></div>
            <div class="password-modal-footer">
                <button class="password-btn password-btn-primary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Password Hapus -->
    <div id="passwordModal" class="password-modal">
        <div class="password-modal-content">
            <div class="password-modal-header">
                <div class="password-icon"><i class="fas fa-lock"></i></div>
                <h3>Verifikasi Keamanan</h3>
                <button class="password-modal-close" onclick="closePasswordModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="password-modal-body">
                <p>Masukkan password admin untuk menghapus data</p>
                <div class="password-input-wrapper">
                    <i class="fas fa-key password-input-icon"></i>
                    <input type="password" id="passwordInput" class="password-input" placeholder="Masukkan password" onkeypress="handlePasswordKeyPress(event)">
                    <button class="password-toggle" onclick="togglePasswordVisibility()" type="button"><i class="fas fa-eye" id="togglePasswordIcon"></i></button>
                </div>
            </div>
            <div class="password-modal-footer">
                <button class="password-btn password-btn-secondary" onclick="closePasswordModal()">Batal</button>
                <button class="password-btn password-btn-primary" onclick="submitPassword()">Verifikasi</button>
            </div>
        </div>
    </div>

    <!-- Modal Reject dengan Catatan -->
    <div id="rejectModal" class="password-modal">
        <div class="password-modal-content" style="max-width: 500px;">
            <div class="password-modal-header">
                <div class="password-icon" style="background: #fee;"><i class="fas fa-times-circle" style="color: #dc3545;"></i></div>
                <h3>Alasan Penolakan</h3>
                <button class="password-modal-close" onclick="closeRejectModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="password-modal-body">
                <p>Berikan alasan mengapa data ini ditolak:</p>
                <textarea id="rejectReason" rows="4" class="form-control" placeholder="Contoh: Data tidak lengkap, foto bukti tidak jelas, dll."></textarea>
            </div>
            <div class="password-modal-footer">
                <button class="password-btn password-btn-secondary" onclick="closeRejectModal()">Batal</button>
                <button class="password-btn password-btn-primary" style="background: #dc3545;" onclick="submitReject()">Tolak</button>
            </div>
        </div>
    </div>
    
    <!-- Modal Approve dengan Catatan (Opsional) -->
<div id="approveModal" class="password-modal">
    <div class="password-modal-content" style="max-width: 500px;">
        <div class="password-modal-header">
            <div class="password-icon" style="background: #d4edda;"><i class="fas fa-check-circle" style="color: #28a745;"></i></div>
            <h3>Setujui Kunjungan</h3>
            <button class="password-modal-close" onclick="closeApproveModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="password-modal-body">
            <p>Berikan catatan (opsional):</p>
            <textarea id="approveReason" rows="4" class="form-control" 
                      placeholder="Contoh: Data lengkap, foto jelas, nasabah kooperatif.&#10;Kosongkan jika tidak ada catatan."></textarea>
            <small class="text-muted" style="display: block; margin-top: 8px;">
                <i class="fas fa-info-circle"></i> Catatan bersifat opsional, tidak wajib diisi.
            </small>
        </div>
        <div class="password-modal-footer">
            <button class="password-btn password-btn-secondary" onclick="closeApproveModal()">Batal</button>
            <button class="password-btn password-btn-primary" style="background: #28a745;" onclick="submitApprove()">
                <i class="fas fa-check-circle"></i> Setujui
            </button>
        </div>
    </div>
</div>

    <!-- Modal User Management -->
    <div id="userModal" class="password-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
        <div style="max-width: 380px; width: 90%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.2); margin: 0 auto;">
            <div style="padding: 10px 15px; text-align: center; border-bottom: 1px solid #eee; position: relative; background: white;">
                <div style="width: 30px; height: 30px; margin: 0 auto 5px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="font-size: 14px; color: white;"></i>
                </div>
                <h3 id="userModalTitle" style="margin: 0; font-size: 15px; font-weight: 600; color: #333;">Tambah User Baru</h3>
                <button onclick="closeUserModal()" style="position: absolute; right: 10px; top: 10px; background: none; border: none; font-size: 14px; cursor: pointer; color: #999;">&times;</button>
            </div>
            <div style="padding: 10px 15px; background: white;">
                <form id="userForm" onsubmit="event.preventDefault(); saveUser();" style="margin: 0;">
                    <input type="hidden" id="userId" value="">
                    <div style="margin-bottom: 8px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 11px; font-weight: 500; color: #333;">
                            <i class="fas fa-user" style="width: 12px; font-size: 10px; margin-right: 4px; color: #667eea;"></i> Nama Lengkap <span style="color: red;">*</span>
                        </label>
                        <input type="text" id="userFullName" class="form-control" placeholder="Masukkan nama lengkap" required autocomplete="off" style="width: 100%; padding: 5px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
                    </div>
                    <div style="margin-bottom: 8px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 11px; font-weight: 500; color: #333;">
                            <i class="fas fa-user-tag" style="width: 12px; font-size: 10px; margin-right: 4px; color: #667eea;"></i> Username <span style="color: red;">*</span>
                        </label>
                        <input type="text" id="userUsername" class="form-control" placeholder="Masukkan username" required autocomplete="off" style="width: 100%; padding: 5px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
                    </div>
                    <div style="margin-bottom: 8px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 11px; font-weight: 500; color: #333;">
                            <i class="fas fa-lock" style="width: 12px; font-size: 10px; margin-right: 4px; color: #667eea;"></i> Password
                        </label>
                        <input type="password" id="userPassword" class="form-control" placeholder="Minimal 4 karakter" autocomplete="off" style="width: 100%; padding: 5px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
                        <small style="font-size: 9px; color: #666; display: block; margin-top: 2px;"><i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <label style="display: block; margin-bottom: 3px; font-size: 11px; font-weight: 500; color: #333;">
                            <i class="fas fa-user-shield" style="width: 12px; font-size: 10px; margin-right: 4px; color: #667eea;"></i> Role <span style="color: red;">*</span>
                        </label>
                        <select id="userRole" class="form-control" required onchange="toggleCabangField()" style="width: 100%; padding: 5px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                            <option value="ao">Account Officer (AO)</option>
                            <option value="manager">Manager Cabang</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <!-- Field untuk Cabang (AO dan Manager) -->
<div id="cabangField" style="margin-bottom: 8px;">
    <label style="display: block; margin-bottom: 3px; font-size: 11px; font-weight: 500; color: #333;">
        <i class="fas fa-store" style="width: 12px; font-size: 10px; margin-right: 4px; color: #667eea;"></i> 
        Cabang <span style="color: red;">*</span>
    </label>
    <select id="userCabang" class="form-control" style="width: 100%; padding: 5px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 5px; background: white;">
        <option value="Pusat">Pusat</option>
        <option value="Kisaran">Kisaran</option>
        <option value="Perdagangan">Perdagangan</option>
        <option value="Pematangsiantar">Pematangsiantar</option>
        <option value="Sidamanik">Sidamanik</option>
        <option value="Stabat">Stabat</option>
    </select>
    <div class="file-info" style="margin-top: 3px;">
        <i class="fas fa-info-circle"></i> Wajib dipilih untuk role AO atau Manager
    </div>
</div>
                    <!-- Field untuk Cabang Binaan (Supervisor) -->
<div id="cabangBinaanField" style="margin-bottom: 8px; display: none;">
    <label style="display: block; margin-bottom: 3px; font-size: 11px; font-weight: 500; color: #333;">
        <i class="fas fa-store" style="width: 12px; font-size: 10px; margin-right: 4px; color: #667eea;"></i> 
        Cabang Binaan <span style="color: red;">*</span>
    </label>
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px; background: #f8f9fa; padding: 8px; border-radius: 6px; border: 1px solid #e0e0e0;">
        <label style="font-size: 11px; display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="cabang_binaan" value="Pusat"> Pusat
        </label>
        <label style="font-size: 11px; display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="cabang_binaan" value="Kisaran"> Kisaran
        </label>
        <label style="font-size: 11px; display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="cabang_binaan" value="Perdagangan"> Perdagangan
        </label>
        <label style="font-size: 11px; display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="cabang_binaan" value="Pematangsiantar"> Pematangsiantar
        </label>
        <label style="font-size: 11px; display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="cabang_binaan" value="Sidamanik"> Sidamanik
        </label>
        <label style="font-size: 11px; display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="cabang_binaan" value="Stabat"> Stabat
        </label>
    </div>
    <div class="file-info" style="margin-top: 5px;">
        <i class="fas fa-info-circle"></i> Pilih cabang yang akan diawasi oleh Supervisor
    </div>
</div>
                </form>
            </div>
            <div style="padding: 8px 15px; display: flex; gap: 8px; justify-content: flex-end; border-top: 1px solid #eee; background: #fafafa;">
                <button onclick="closeUserModal()" style="padding: 4px 10px; font-size: 11px; border-radius: 4px; cursor: pointer; background: #6c757d; color: white; border: none;">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button onclick="saveUser()" style="padding: 4px 10px; font-size: 11px; border-radius: 4px; cursor: pointer; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal Password untuk Hapus/Edit User -->
<div id="userPasswordModal" class="password-modal">
    <div class="password-modal-content" style="max-width: 400px;">
        <div class="password-modal-header">
            <div class="password-icon"><i class="fas fa-lock"></i></div>
            <h3 id="userPasswordModalTitle">Verifikasi Keamanan</h3>
            <button class="password-modal-close" onclick="closeUserPasswordModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="password-modal-body">
            <p id="userPasswordModalMessage">Masukkan password admin untuk melanjutkan</p>
            <div class="password-input-wrapper">
                <i class="fas fa-key password-input-icon"></i>
                <input type="password" id="userPasswordInput" class="password-input" placeholder="Masukkan password admin" onkeypress="handleUserPasswordKeyPress(event)">
                <button class="password-toggle" onclick="toggleUserPasswordVisibility()" type="button"><i class="fas fa-eye" id="toggleUserPasswordIcon"></i></button>
            </div>
            <div id="userPasswordError" style="color: #dc3545; font-size: 12px; margin-top: 8px; display: none;"></div>
        </div>
        <div class="password-modal-footer">
            <button class="password-btn password-btn-secondary" onclick="closeUserPasswordModal()">Batal</button>
            <button class="password-btn password-btn-primary" id="userPasswordConfirmBtn" onclick="submitUserPassword()">Verifikasi</button>
        </div>
    </div>
</div>
    
    <!-- Modal Log Activity -->
<div id="logModal" class="password-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="max-width: 1200px; width: 95%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.2); margin: 0 auto; max-height: 90%; display: flex; flex-direction: column;">
        
        <!-- HEADER -->
        <div style="padding: 12px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 16px;"><i class="fas fa-history"></i> Log Activity</h3>
            <button onclick="closeLogModal()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        
        <!-- FILTER -->
        <div style="padding: 12px 20px; background: #f8f9fa; border-bottom: 1px solid #ddd; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="text" id="logSearch" placeholder="Cari..." style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 5px; flex: 1;">
            <select id="logModule" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="">Semua Module</option>
                <option value="KUNJUNGAN">Kunjungan</option>
                <option value="USER">User</option>
                <option value="LOGIN">Login/Logout</option>
            </select>
            <select id="logAction" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="">Semua Aksi</option>
                <option value="CREATE">Create</option>
                <option value="UPDATE">Update</option>
                <option value="DELETE">Delete</option>
                <option value="APPROVE">Approve</option>
                <option value="REJECT">Reject</option>
                <option value="LOGIN">Login</option>
                <option value="LOGOUT">Logout</option>
            </select>
            <input type="date" id="logStartDate" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 5px;">
            <span>s/d</span>
            <input type="date" id="logEndDate" style="padding: 6px 10px; border: 1px solid #ddd; border-radius: 5px;">
            <button onclick="loadLogs()" class="btn-primary" style="padding: 6px 15px;"><i class="fas fa-search"></i> Filter</button>
            <button onclick="resetLogFilters()" class="btn-secondary" style="padding: 6px 15px;"><i class="fas fa-sync-alt"></i> Reset</button>
            <button onclick="exportLogs()" class="btn-success" style="padding: 6px 15px;"><i class="fas fa-download"></i> Export</button>
        </div>
        
        <!-- STATISTIK -->
        <div id="logStats" style="padding: 10px 20px; background: #e9ecef; display: flex; gap: 20px; flex-wrap: wrap; font-size: 12px;">
            <!-- Stats akan diisi JS -->
        </div>
        
        <!-- BODY TABEL -->
        <div style="padding: 15px 20px; overflow-y: auto; flex: 1;">
            <div class="table-container">
                <table style="min-width: 800px; width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Aksi</th>
                            <th>Module</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        <tr><td colspan="8" style="text-align: center;">Memuat data...<\/td></tr>
                    </tbody>
                </table>
            </div>
            <div id="logPagination" style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <span id="logPageInfo">Halaman 1</span>
                <div>
                    <button id="logPrevPage" class="btn-secondary" style="padding: 4px 10px;">&laquo; Sebelumnya</button>
                    <button id="logNextPage" class="btn-secondary" style="padding: 4px 10px;">Selanjutnya &raquo;</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL LIHAT HASIL KUNJUNGAN (DILUAR logModal) ========== -->
    <div id="lihatHasilModal" class="detail-modal">
        <div class="detail-modal-content" style="max-width: 500px;">
            <div class="detail-modal-header">
                <div class="modal-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Detail Hasil Kunjungan</h3>
                <button class="detail-modal-close" onclick="closeLihatHasilModal()">&times;</button>
            </div>
            <div class="detail-modal-body" id="hasilLengkapBody" style="white-space: pre-wrap; word-wrap: break-word;">
                -
            </div>
            <div class="password-modal-footer">
                <button class="password-btn password-btn-primary" onclick="closeLihatHasilModal()">Tutup</button>
            </div>
        </div>
    </div>
    
    <!-- Reminder Banner -->
<div id="reminderBanner" class="reminder-banner">
    <div class="reminder-content">
        <i class="fas fa-bell"></i>
        <div class="reminder-text">
            <strong>⚠️ Perhatian!</strong> Terdapat <span id="reminderCount">0</span> data kunjungan yang sudah <strong>lebih dari 7 hari</strong> belum disetujui!
        </div>
    </div>
    <button class="btn-view" onclick="filterOldPendingData()">
        <i class="fas fa-eye"></i> Lihat Sekarang
    </button>
</div>

<!-- Auto Save Indicator -->
<div id="autoSaveIndicator" class="auto-save-indicator">
    <i class="fas fa-save"></i>
    <span id="autoSaveMessage">Draft tersimpan</span>
</div>

    <!-- Toast Notification -->
    <div id="toastNotification" class="toast-notification">
        <i class="fas fa-check-circle"></i> <span id="toastMessage"></span>
    </div>
    
    <!-- Loading Overlay untuk seluruh halaman -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-card">
        <div class="spinner"></div>
        <p>Memproses data <span class="dots">...</span></p>
    </div>
</div>

<!-- Loading Bar di atas halaman -->
<div id="loadingBar" class="loading-bar"></div>

    <script>
    // ==================== VARIABLES ====================
    let currentUser = null;
    let allData = [];
    let filteredData = [];
    let currentPage = 1;
    let rowsPerPage = 10;
    let deleteId = null;
    let rejectId = null;
    let dateFilterStart = null;
    let dateFilterEnd = null;
    let statusFilter = 'all';
    
    // ==================== LOADING FUNCTIONS (DITEMPATKAN DI AWAL) ====================

// Loading Bar
let loadingBarInterval = null;

function startLoadingBar() {
    let bar = document.getElementById('loadingBar');
    
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'loadingBar';
        bar.className = 'loading-bar';
        document.body.appendChild(bar);
    }
    
    let width = 0;
    bar.style.display = 'block';
    bar.style.width = '0%';
    
    if (loadingBarInterval) clearInterval(loadingBarInterval);
    
    loadingBarInterval = setInterval(() => {
        if (width < 90) {
            width += Math.random() * 10;
            if (width > 90) width = 90;
            bar.style.width = width + '%';
        }
    }, 200);
}

function finishLoadingBar() {
    const bar = document.getElementById('loadingBar');
    if (!bar) return;
    
    if (loadingBarInterval) {
        clearInterval(loadingBarInterval);
        loadingBarInterval = null;
    }
    
    bar.style.width = '100%';
    setTimeout(() => {
        bar.style.display = 'none';
        bar.style.width = '0%';
    }, 300);
}

// Loading overlay
let loadingTimeout = null;

function showLoading(message = 'Memproses data...') {
    let overlay = document.getElementById('loadingOverlay');
    
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-card">
                <div class="spinner"></div>
                <p id="loadingMessage">Memproses data...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    
    const loadingMessage = document.getElementById('loadingMessage');
    if (loadingMessage) {
        loadingMessage.textContent = message;
    }
    
    overlay.classList.add('show');
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
    if (loadingTimeout) {
        clearInterval(loadingTimeout);
        loadingTimeout = null;
    }
}

// Loading untuk tombol
function setButtonLoading(button, isLoading, originalText = null) {
    if (!button) return;
    
    if (isLoading) {
        if (!originalText) {
            originalText = button.innerHTML;
            button.setAttribute('data-original-text', originalText);
        }
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        button.disabled = true;
        button.classList.add('btn-loading');
    } else {
        const savedText = button.getAttribute('data-original-text');
        if (savedText) {
            button.innerHTML = savedText;
        }
        button.disabled = false;
        button.classList.remove('btn-loading');
    }
}

// Loading skeleton untuk tabel
function showTableSkeleton() {
    const tbody = document.getElementById('tableBody');
    if (!tbody) return;
    
    const skeletonRows = [];
    for (let i = 0; i < 5; i++) {
        skeletonRows.push(`
            <tr class="skeleton-row">
                <td><div class="skeleton-cell" style="width: 30px;"></div></td>
                <td><div class="skeleton-cell" style="width: 80px;"></div></td>
                <td><div class="skeleton-cell" style="width: 100px;"></div></td>
                <td><div class="skeleton-cell" style="width: 120px;"></div></td>
                <td><div class="skeleton-cell" style="width: 100px;"></div></td>
                <td><div class="skeleton-cell" style="width: 150px;"></div></td>
                <td><div class="skeleton-cell" style="width: 90px;"></div></td>
                <td><div class="skeleton-cell" style="width: 80px;"></div></td>
                <td><div class="skeleton-cell" style="width: 100px;"></div></td>
                <td><div class="skeleton-cell" style="width: 80px;"></div></td>
                <td><div class="skeleton-cell" style="width: 60px;"></div></td>
                <td><div class="skeleton-cell" style="width: 80px;"></div></td>
                <td><div class="skeleton-cell" style="width: 100px;"></div></td>
            </tr>
        `);
    }
    tbody.innerHTML = skeletonRows.join('');
}

// Loading untuk card
function showCardLoading(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.innerHTML = `
        <div class="loading-card-simple">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Memuat data...</p>
        </div>
    `;
}

    // ==================== HELPER FUNCTIONS ====================
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function showToast(message, isSuccess = true) {
        const toast = document.getElementById('toastNotification');
        const toastMessage = document.getElementById('toastMessage');
        if (toast && toastMessage) {
            toastMessage.innerHTML = message;
            toast.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545';
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    }

    function showMessage(msg, type) {
        const msgDiv = document.getElementById('message');
        if (msgDiv) {
            msgDiv.textContent = msg;
            msgDiv.className = 'message ' + type;
            setTimeout(() => msgDiv.className = 'message', 3000);
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function hanyaAngka(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            event.preventDefault();
            return false;
        }
        return true;
    }
    
    
    // ==================== FUNGSI HAPUS PREVIEW FOTO ====================
function removeFotoPreview() {
    console.log('removeFotoPreview dipanggil'); // Debugging
    
    const fileInput = document.getElementById('foto');
    const previewContainer = document.getElementById('fotoPreviewContainer');
    const previewImg = document.getElementById('fotoPreviewImg');
    const previewName = document.getElementById('fotoPreviewName');
    const previewSize = document.getElementById('fotoPreviewSize');
    
    // Kosongkan input file
    if (fileInput) {
        fileInput.value = '';
    }
    
    // Sembunyikan container preview
    if (previewContainer) {
        previewContainer.style.display = 'none';
        previewContainer.classList.remove('show');
    }
    
    // Kosongkan gambar preview
    if (previewImg) {
        previewImg.src = '';
    }
    
    // Kosongkan nama file
    if (previewName) {
        previewName.textContent = '';
    }
    
    // Kosongkan ukuran file
    if (previewSize) {
        previewSize.textContent = '';
    }
    
    showToast('Foto preview dihapus', true);
}
    
    

    function toggleLoginPassword() {
        const input = document.getElementById('loginPassword');
        const icon = document.getElementById('loginToggleIcon');
        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('togglePasswordIcon');
        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    function toggleCabangField() {
    const roleElement = document.getElementById('userRole');
    const cabangField = document.getElementById('cabangField');
    const cabangBinaanField = document.getElementById('cabangBinaanField');
    
    if (roleElement && cabangField && cabangBinaanField) {
        if (roleElement.value === 'admin') {
            cabangField.style.display = 'none';
            cabangBinaanField.style.display = 'none';
        } 
        else if (roleElement.value === 'supervisor') {
            cabangField.style.display = 'none';
            cabangBinaanField.style.display = 'block';
        }
        else { // ao atau manager
            cabangField.style.display = 'block';
            cabangBinaanField.style.display = 'none';
        }
    }
}

    // ==================== MODAL DETAIL ====================
    function showDetail(title, content) {
        const modal = document.getElementById('detailModal');
        const modalTitle = document.getElementById('detailModalTitle');
        const modalBody = document.getElementById('detailModalBody');
        
        if (modal && modalTitle && modalBody) {
            modalTitle.innerHTML = title;
            modalBody.innerHTML = '<div style="white-space: pre-wrap; word-wrap: break-word; line-height: 1.6;">' + escapeHtml(content) + '</div>';
            modal.classList.add('show');
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        if (modal) modal.classList.remove('show');
    }
    
    // ==================== MODAL LIHAT HASIL KUNJUNGAN ====================
function showHasilDetail(hasil) {
    const modal = document.getElementById('lihatHasilModal');
    const body = document.getElementById('hasilLengkapBody');
    
    if (modal && body) {
        body.innerHTML = hasil ? hasil.replace(/\n/g, '<br>') : '<em class="text-muted">Tidak ada hasil kunjungan</em>';
        modal.classList.add('show');
    }
}

function closeLihatHasilModal() {
    const modal = document.getElementById('lihatHasilModal');
    if (modal) modal.classList.remove('show');
}

    // ==================== FILTER FUNCTIONS ====================
    function filterByDate() {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        
        dateFilterStart = startDate || null;
        dateFilterEnd = endDate || null;
        
        applyFilters();
        showToast(`Filter tanggal: ${dateFilterStart || 'awal'} s/d ${dateFilterEnd || 'akhir'}`, true);
    }
    
    function clearAllFilters() {
    // Reset date filters
    document.getElementById('filterStartDate').value = '';
    document.getElementById('filterEndDate').value = '';
    dateFilterStart = null;
    dateFilterEnd = null;
    
    // Reset status filter (TAMBAHKAN INI)
    const statusSelect = document.getElementById('filterStatus');
    if (statusSelect) {
        statusSelect.value = 'all';
        statusFilter = 'all';
    }
    
    // Reset search
    document.getElementById('searchInput').value = '';
    
    // Apply all filters (which will now show all data)
    applyFilters();
    showToast('Semua filter dihapus', true);
}
    
    // ==================== UPDATE AO DROPDOWN ====================
function updateAODropdown() {
    const aoSelect = document.getElementById('filterAOAdvanced');
    if (!aoSelect) return;
    
    // Ambil semua AO unik dari allData
    const aoList = [...new Set(allData.map(item => item.nama_ao).filter(Boolean))];
    
    aoSelect.innerHTML = '<option value="">Semua AO</option>';
    aoList.forEach(ao => {
        aoSelect.innerHTML += `<option value="${escapeHtml(ao)}">${escapeHtml(ao)}</option>`;
    });
}
    
    function applyFilters() {
    let result = [...allData];
    
    // Filter tanggal
    if (dateFilterStart || dateFilterEnd) {
        result = result.filter(item => {
            const tgl = item.tanggal_kunjungan;
            if (!tgl) return false;
            if (dateFilterStart && tgl < dateFilterStart) return false;
            if (dateFilterEnd && tgl > dateFilterEnd) return false;
            return true;
        });
    }
    
    // Filter status (TAMBAHKAN INI)
    if (statusFilter && statusFilter !== 'all') {
        result = result.filter(item => item.status === statusFilter);
    }
    
    // Search keyword
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
    if (keyword !== '') {
        result = result.filter(item => {
            return (
                (item.nama_ao?.toLowerCase().includes(keyword)) ||
                (item.nama_cabang?.toLowerCase().includes(keyword)) ||
                (item.nama_nasabah?.toLowerCase().includes(keyword)) ||
                (item.no_pembiayaan?.toString().toLowerCase().includes(keyword)) ||
                (item.alamat?.toLowerCase().includes(keyword))
            );
        });
    }
    
    filteredData = result;
    currentPage = 1;
    renderTable();
    
    // Reset checkbox select all
    const selectAll = document.getElementById('selectAllCheckbox');
    if (selectAll) selectAll.checked = false;
    updateSelectedCount();
}

function filterByStatus() {
    const statusSelect = document.getElementById('filterStatus');
    if (statusSelect) {
        statusFilter = statusSelect.value;
        applyFilters();
        
        const statusText = statusSelect.options[statusSelect.selectedIndex].text;
        if (statusFilter !== 'all') {
            showToast(`Menampilkan data dengan status: ${statusText}`, true);
        } else {
            showToast('Menampilkan semua status', true);
        }
    }
}

    // ==================== STATUS BADGE ====================
    function getStatusBadge(status) {
        if (status === 'approved') {
            return '<span class="status-badge status-approved"><i class="fas fa-check-circle"></i> Disetujui</span>';
        } else if (status === 'rejected') {
            return '<span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>';
        } else {
            return '<span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>';
        }
    }

    function renderTable() {
    const start = (currentPage - 1) * rowsPerPage;
    const pageData = filteredData.slice(start, start + rowsPerPage);
    const tbody = document.getElementById('tableBody');
    
    if (pageData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="15" style="text-align: center;">Tidak ada data</td></tr>';
        document.getElementById('startRecord').textContent = '0';
        document.getElementById('endRecord').textContent = '0';
        document.getElementById('totalRecords').textContent = filteredData.length;
        document.getElementById('prevPage').disabled = true;
        document.getElementById('nextPage').disabled = true;
        document.getElementById('pageInfo').innerHTML = `Halaman <strong>1</strong> dari <strong>1</strong>`;
        return;
    }
    
    tbody.innerHTML = pageData.map((item, idx) => {
        const no = start + idx + 1;
        const statusHtml = getStatusBadge(item.status);
        
        // Hasil Kunjungan HTML
        let hasilKunjunganHtml = '-';
        if (item.hasil_kunjungan) {
            let icon = 'fa-flag-checkered';
            let color = '#17a2b8';
            if (item.hasil_kunjungan.includes('Nasabah Ditemui')) {
                icon = 'fa-user-check';
                color = '#28a745';
            } else if (item.hasil_kunjungan.includes('Nasabah Tidak Ditemui')) {
                icon = 'fa-user-slash';
                color = '#dc3545';
            } else if (item.hasil_kunjungan.includes('Angsuran Lancar')) {
                icon = 'fa-check-circle';
                color = '#28a745';
            } else if (item.hasil_kunjungan.includes('Angsuran Bermasalah')) {
                icon = 'fa-exclamation-triangle';
                color = '#fd7e14';
            } else if (item.hasil_kunjungan.includes('Restrukturisasi')) {
                icon = 'fa-handshake';
                color = '#6f42c1';
            } else if (item.hasil_kunjungan.includes('Monitoring')) {
                icon = 'fa-chart-line';
                color = '#17a2b8';
            }
            const displayText = item.hasil_kunjungan.length > 25 ? item.hasil_kunjungan.substring(0, 25) + '...' : item.hasil_kunjungan;
            hasilKunjunganHtml = `<span style="display: inline-flex; align-items: center; gap: 5px; background: #e3f2fd; padding: 4px 8px; border-radius: 20px; font-size: 11px; cursor: pointer;" 
                                       onclick="showHasilDetail('${escapeHtml(item.hasil_kunjungan).replace(/'/g, "\\'")}')" 
                                       title="Klik untuk lihat lengkap">
                                    <i class="fas ${icon}" style="color: ${color};"></i>
                                    ${escapeHtml(displayText)}
                                    <i class="fas fa-expand-alt" style="color: #999; font-size: 9px; margin-left: 3px;"></i>
                                </span>`;
        }
        
        // Foto thumbnail
        let fotoHtml = '-';
        if (item.foto_url) {
            fotoHtml = `<a href="${item.foto_url}" target="_blank" title="Klik untuk lihat foto" style="display: inline-block;">
                            <img src="${item.foto_url}" alt="Foto" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 1px solid #ddd;">
                        </a>`;
        }
        
        const keterangan = item.keterangan || '-';
        const keteranganDisplay = (keterangan !== '-' && keterangan.length > 100) ? 
            `<span class="clickable-text" onclick='showDetail("Keterangan", ${JSON.stringify(keterangan).replace(/'/g, "\\'")})' title="Klik untuk lihat lengkap" style="display: inline-block;">${escapeHtml(keterangan.substring(0, 25))}...</span>` : 
            `<span title="${escapeHtml(keterangan)}" style="display: inline-block;">${escapeHtml(keterangan)}</span>`;
        
        const alamat = item.alamat || '-';
        const alamatDisplay = (alamat !== '-' && alamat.length > 35) ? 
            `<span class="clickable-text" onclick='showDetail("Alamat Lengkap", ${JSON.stringify(alamat).replace(/'/g, "\\'")})' title="Klik untuk lihat lengkap" style="display: inline-block;">${escapeHtml(alamat.substring(0, 35))}...</span>` : 
            `<span title="${escapeHtml(alamat)}" style="display: inline-block;">${escapeHtml(alamat)}</span>`;
        
        const catatan = item.catatan_manager || '-';
        let catatanDisplay = '-';
        if (catatan !== '-' && catatan !== '') {
            const displayText = catatan.length > 20 ? catatan.substring(0, 20) + '...' : catatan;
            catatanDisplay = `<span class="catatan-badge" onclick='showDetail("Catatan", ${JSON.stringify(catatan).replace(/'/g, "\\'")})' title="Klik untuk lihat lengkap" style="display: inline-flex; align-items: center; gap: 4px;"><i class="fas fa-exclamation-triangle"></i> ${escapeHtml(displayText)}</span>`;
        }
        
        let waktuInput = '-';
        if (item.waktu_input) {
            const date = new Date(item.waktu_input);
            waktuInput = date.toLocaleDateString('id-ID', { 
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }
        
        let actionButtons = '';
        if (currentUser && currentUser.role === 'ao') {
            if (item.status === 'pending' || item.status === 'rejected') {
                actionButtons += `<button class="action-btn edit" onclick='editData(${JSON.stringify(item).replace(/'/g, "\\'")})'><i class="fas fa-edit"></i> Edit</button>`;
            }
            if (item.status === 'approved') {
                actionButtons += `<span class="info-badge success"><i class="fas fa-check-circle"></i> Disetujui</span>`;
            }
            if (item.status === 'rejected') {
                actionButtons += `<span class="info-badge warning"><i class="fas fa-info-circle"></i> Perbaiki data</span>`;
            }
        }
        
        if (currentUser && currentUser.role === 'admin') {
            actionButtons += `<button class="action-btn delete" onclick="deleteDataWithModal(${item.id})"><i class="fas fa-trash"></i> Hapus</button>`;
        }
        
        if (currentUser && currentUser.role === 'manager') {
            if (item.status === 'pending') {
                actionButtons += `<button class="action-btn approve" onclick="openApproveModal(${item.id})"><i class="fas fa-check-circle"></i> Approve</button>`;
                actionButtons += `<button class="action-btn reject" onclick="openRejectModal(${item.id})"><i class="fas fa-times-circle"></i> Reject</button>`;
            } else if (item.status === 'rejected') {
                actionButtons += `<button class="action-btn approve" onclick="openApproveModal(${item.id})"><i class="fas fa-check-circle"></i> Approve</button>`;
                actionButtons += `<span class="info-badge warning"><i class="fas fa-clock"></i> Menunggu Perbaikan</span>`;
            } else if (item.status === 'approved') {
                actionButtons += `<button class="action-btn cancel" onclick="cancelApprove(${item.id})"><i class="fas fa-undo-alt"></i> Batal Approve</button>`;
            }
        }
        
        return `
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="row-checkbox" data-id="${item.id}" onclick="updateSelectAllState()" style="width: 16px; height: 16px; cursor: pointer;">
                </td>
                <td style="text-align: center; vertical-align: middle;">${no}</td>
                <td style="vertical-align: middle;">${escapeHtml(item.nama_cabang || '')}</td>
                <td style="vertical-align: middle;">${escapeHtml(item.nama_ao || '')}</td>
                <td style="vertical-align: middle;">${escapeHtml(item.nama_nasabah || '')}</td>
                <td style="vertical-align: middle;">${escapeHtml(item.no_pembiayaan || '')}</td>
                <td style="vertical-align: middle;">${alamatDisplay}</td>
                <td style="text-align: center; vertical-align: middle;">${item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : ''}</td>
                <td style="vertical-align: middle;">${keteranganDisplay}</td>
                <td style="text-align: center; vertical-align: middle;">${hasilKunjunganHtml}</td>
                <td style="text-align: center; vertical-align: middle;">${waktuInput}</td>
                <td style="text-align: center; vertical-align: middle;">${fotoHtml}</td>
                <td style="text-align: center; vertical-align: middle;">${statusHtml}</td>
                <td style="text-align: center; vertical-align: middle;">${catatanDisplay}</td>
                <td style="text-align: center; vertical-align: middle;"><div class="action-buttons">${actionButtons || '-'}</div></td>
            </tr>
        `;
    }).join('');
    
    document.getElementById('startRecord').textContent = filteredData.length > 0 ? start + 1 : 0;
    document.getElementById('endRecord').textContent = Math.min(start + rowsPerPage, filteredData.length);
    document.getElementById('totalRecords').textContent = filteredData.length;
    document.getElementById('pageInfo').textContent = `Halaman ${currentPage}`;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = start + rowsPerPage >= filteredData.length;
    
    setTimeout(() => {
        updateSelectAllState();
    }, 100);
}

// ==================== FUNGSI CHECKBOX DAN EXPORT SELECTED ====================

// 1. Fungsi untuk Select All / Pilih Semua
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    
    if (selectAll) {
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        updateSelectedCount();
    }
}

// 2. Fungsi untuk update status checkbox "Select All"
function updateSelectAllState() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
    
    if (selectAll && checkboxes.length > 0) {
        if (checkedCount === 0) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        } else if (checkedCount === checkboxes.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = true;  // Status centang setengah ( - )
        }
    }
    
    updateSelectedCount();
}

// 3. Fungsi untuk update jumlah data yang dipilih
function updateSelectedCount() {
    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
    const selectedCountSpan = document.getElementById('selectedCount');
    if (selectedCountSpan) {
        selectedCountSpan.textContent = checkedCount;
    }
}

// 4. Fungsi untuk mendapatkan data yang dipilih
function getSelectedData() {
    const selectedIds = [];
    
    // Ambil semua ID dari checkbox yang dicentang
    document.querySelectorAll('.row-checkbox:checked').forEach(checkbox => {
        const id = parseInt(checkbox.getAttribute('data-id'));
        selectedIds.push(id);
    });
    
    // Filter data berdasarkan ID yang dipilih
    return allData.filter(item => selectedIds.includes(item.id));
}

// 5. Fungsi untuk export data yang dipilih
async function exportSelected() {
    const selectedData = getSelectedData();
    
    if (selectedData.length === 0) {
        showToast('Pilih minimal satu data untuk diexport!', false);
        return;
    }
    
    // Tanya user mau export ke Excel atau PDF
    const format = confirm('Export ke Excel? (OK = Excel, Cancel = PDF)');
    
    if (format) {
        exportToExcel(selectedData);
    } else {
        await exportToPDF(selectedData);
    }
}

    // ==================== APPROVE, REJECT, CANCEL APPROVE ====================
    async function approveData(id) {
        if (!confirm('Yakin ingin menyetujui data ini?')) return;
        
         // Tampilkan loading
    startLoadingBar();
    showLoading('Menyetujui data...');
        
        try {
            const response = await fetch(`/api/kunjungan/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include'
            });
            
            const result = await response.json();
            if (result.success) {
                showMessage('Data berhasil disetujui', 'success');
                showToast('Data berhasil disetujui', true);
                loadData();
            } else {
                showMessage(result.message || 'Gagal menyetujui data', 'error');
                showToast(result.message || 'Gagal menyetujui data', false);
            }
        } catch (error) {
            console.error('Error approving data:', error);
            showMessage('Terjadi kesalahan', 'error');
            showToast('Terjadi kesalahan', false);
        } finally {
        hideLoading();
        finishLoadingBar();
    }
    }

    async function cancelApprove(id) {
        if (!confirm('Yakin ingin membatalkan persetujuan data ini?\n\nData akan kembali ke status Pending.')) return;
        
        try {
            const response = await fetch(`/api/kunjungan/${id}/cancel-approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include'
            });
            
            const result = await response.json();
            if (result.success) {
                showMessage('Persetujuan berhasil dibatalkan', 'success');
                showToast('Persetujuan berhasil dibatalkan', true);
                loadData();
            } else {
                showMessage(result.message || 'Gagal membatalkan persetujuan', 'error');
                showToast(result.message || 'Gagal membatalkan persetujuan', false);
            }
        } catch (error) {
            console.error('Error cancel approve:', error);
            showMessage('Terjadi kesalahan: ' + error.message, 'error');
            showToast('Terjadi kesalahan: ' + error.message, false);
        }
    }

    function openRejectModal(id) {
        rejectId = id;
        document.getElementById('rejectReason').value = '';
        document.getElementById('rejectModal').classList.add('show');
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('show');
        rejectId = null;
    }
    
        async function submitReject() {
        const reason = document.getElementById('rejectReason').value.trim();
        if (!reason) {
            alert('Harap berikan alasan penolakan!');
            return;
        }
        
        try {
            const response = await fetch(`/api/kunjungan/${rejectId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include',
                body: JSON.stringify({ catatan_manager: reason })
            });
            
            const result = await response.json();
            if (result.success) {
                showMessage('Data berhasil ditolak', 'success');
                showToast('Data berhasil ditolak', true);
                closeRejectModal();
                loadData();
            } else {
                showMessage(result.message || 'Gagal menolak data', 'error');
                showToast(result.message || 'Gagal menolak data', false);
            }
        } catch (error) {
            console.error('Error rejecting data:', error);
            showMessage('Terjadi kesalahan', 'error');
            showToast('Terjadi kesalahan', false);
        }
    }

    // ========== TARUH KODE INI DI SINI ==========
    // ========== FUNGSI APPROVE DENGAN CATATAN OPSIONAL ==========
    let approveId = null;

    function openApproveModal(id) {
        approveId = id;
        document.getElementById('approveReason').value = '';
        document.getElementById('approveModal').classList.add('show');
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.remove('show');
        approveId = null;
    }

    async function submitApprove() {
        const catatan = document.getElementById('approveReason').value.trim();
        
        try {
            const response = await fetch(`/api/kunjungan/${approveId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include',
                body: JSON.stringify({ 
                    catatan_manager: catatan || null,
                    status: 'approved'
                })
            });
            
            const result = await response.json();
            if (result.success) {
                showMessage('Data berhasil disetujui' + (catatan ? ' dengan catatan' : ''), 'success');
                showToast('Data berhasil disetujui' + (catatan ? ' dengan catatan' : ''), true);
                closeApproveModal();
                loadData();
            } else {
                showMessage(result.message || 'Gagal menyetujui data', 'error');
                showToast(result.message || 'Gagal menyetujui data', false);
            }
        } catch (error) {
            console.error('Error approving data:', error);
            showMessage('Terjadi kesalahan', 'error');
            showToast('Terjadi kesalahan', false);
        }
    }
    // ========== SAMPAI SINI ==========

    // ==================== DELETE WITH PASSWORD ====================
    function deleteDataWithModal(id) {
        deleteId = id;
        document.getElementById('passwordModal').classList.add('show');
        document.getElementById('passwordInput').focus();
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('show');
        deleteId = null;
        document.getElementById('passwordInput').value = '';
    }

    function handlePasswordKeyPress(event) {
        if (event.key === 'Enter') submitPassword();
    }

    async function submitPassword() {
        const password = document.getElementById('passwordInput')?.value;
        if (!password) { 
            alert('Masukkan password admin'); 
            return;
        }
        
        if (password === '12345') {
            document.getElementById('loadingIndicator').style.display = 'block';
            
            try {
                const response = await fetch(`/api/kunjungan/${deleteId}`, { 
                    method: 'DELETE', 
                    headers: { 
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showMessage(result.message, 'success');
                    showToast(result.message, true);
                    loadData();
                    closePasswordModal();
                } else {
                    showMessage(result.message || 'Gagal menghapus data', 'error');
                    showToast(result.message || 'Gagal menghapus data', false);
                }
            } catch (error) {
                console.error('Delete error:', error);
                showMessage('Terjadi kesalahan', 'error');
                showToast('Terjadi kesalahan', false);
            } finally {
                document.getElementById('loadingIndicator').style.display = 'none';
            }
        } else {
            alert('Password salah!');
            document.getElementById('passwordInput').value = '';
            document.getElementById('passwordInput').focus();
        }
    }

    // ==================== CRUD KUNJUNGAN ====================
    async function loadData() {
    // ========== TAMPILKAN LOADING ==========
    startLoadingBar();
    showTableSkeleton();
    
    try {
        const response = await fetch('/api/kunjungan', { 
            headers: { 
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            },
            credentials: 'include'
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (currentUser?.role === 'ao') {
                allData = result.data.filter(item => 
                    item.nama_cabang === currentUser.cabang && 
                    item.nama_ao === currentUser.name
                );
                document.getElementById('totalKunjungan').textContent = allData.length;
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('hariIni').textContent = allData.filter(item => item.tanggal_kunjungan === today).length;
                document.getElementById('totalCabang').textContent = '1';
                const pendingCount = allData.filter(item => item.status === 'pending').length;
                document.getElementById('pendingCount').textContent = pendingCount;
            } 
            else if (currentUser?.role === 'manager') {
                allData = result.data.filter(item => item.nama_cabang === currentUser.cabang);
                document.getElementById('totalKunjungan').textContent = allData.length;
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('hariIni').textContent = allData.filter(item => item.tanggal_kunjungan === today).length;
                document.getElementById('totalCabang').textContent = '1';
                const pendingCount = allData.filter(item => item.status === 'pending').length;
                document.getElementById('pendingCount').textContent = pendingCount;
            }
            else if (currentUser?.role === 'supervisor') {
                // Supervisor: lihat semua data (atau cabang binaan jika diimplementasikan)
                allData = result.data;
                document.getElementById('totalKunjungan').textContent = allData.length;
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('hariIni').textContent = allData.filter(item => item.tanggal_kunjungan === today).length;
                // Hitung jumlah cabang unik
                const uniqueCabangs = [...new Set(allData.map(item => item.nama_cabang))];
                document.getElementById('totalCabang').textContent = uniqueCabangs.length;
                const pendingCount = allData.filter(item => item.status === 'pending').length;
                document.getElementById('pendingCount').textContent = pendingCount;
            } 
            else {
                allData = result.data;
                document.getElementById('totalCabang').textContent = result.stats?.total_cabang || 0;
                document.getElementById('totalKunjungan').textContent = result.stats?.total_kunjungan || 0;
                document.getElementById('hariIni').textContent = result.stats?.hari_ini || 0;
                const pendingCount = allData.filter(item => item.status === 'pending').length;
                document.getElementById('pendingCount').textContent = pendingCount;
            }
            
            filteredData = [...allData];
            document.getElementById('tanggalKunjungan').value = new Date().toISOString().split('T')[0];
            
            if (currentUser?.role === 'ao' && currentUser.cabang) {
                document.getElementById('namaCabang').value = currentUser.cabang;
                document.getElementById('namaCabang').disabled = true;
                document.getElementById('namaAO').value = currentUser.name;
                document.getElementById('namaAO').disabled = true;
            }
            
            updateAODropdown();
            renderTable();

            // Load dashboard jika user berhak
if (currentUser && (currentUser.role === 'manager' || currentUser.role === 'admin' || currentUser.role === 'supervisor')) {
    await loadDashboard();
}
        }
        
        // ==================== REMINDER KUNJUNGAN ====================

// Cek data yang sudah lama belum di-approve (> 7 hari)
function checkReminderData() {
    if (!currentUser) return;
    if (currentUser.role !== 'manager' && currentUser.role !== 'admin') return;
    
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
    
    const oldPendingData = allData.filter(item => {
        if (item.status !== 'pending') return false;
        
        const tglKunjungan = new Date(item.tanggal_kunjungan);
        return tglKunjungan < sevenDaysAgo;
    });
    
    const reminderBanner = document.getElementById('reminderBanner');
    const reminderCount = document.getElementById('reminderCount');
    
    if (reminderBanner && reminderCount) {
        if (oldPendingData.length > 0) {
            reminderCount.textContent = oldPendingData.length;
            reminderBanner.classList.add('show');
            
            // Simpan data untuk filter
            window.oldPendingData = oldPendingData;
        } else {
            reminderBanner.classList.remove('show');
        }
    }
}

// Filter data yang sudah lama
function filterOldPendingData() {
    if (window.oldPendingData && window.oldPendingData.length > 0) {
        filteredData = window.oldPendingData;
        currentPage = 1;
        renderTable();
        showToast(`Menampilkan ${filteredData.length} data yang perlu segera diproses`, true);
        
        // Scroll ke tabel
        document.querySelector('.table-section').scrollIntoView({ behavior: 'smooth' });
    }
}

// Kirim notifikasi email (opsional - via backend)
async function sendReminderEmail() {
    if (!window.oldPendingData || window.oldPendingData.length === 0) return;
    
    try {
        const response = await fetch('/api/send-reminder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                count: window.oldPendingData.length,
                data: window.oldPendingData
            })
        });
        const result = await response.json();
        if (result.success) {
            showToast('Notifikasi reminder telah dikirim', true);
        }
    } catch (error) {
        console.error('Error sending reminder:', error);
    }
}

// Panggil checkReminderData() di dalam loadData()
// Tambahkan di akhir fungsi loadData() setelah renderTable()
        
    } catch (error) {
        console.error('Load data error:', error);
        showMessage('Gagal memuat data', 'error');
        showToast('Gagal memuat data', false);
        
        const tbody = document.getElementById('tableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="15" class="text-center" style="color: red; padding: 40px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                Gagal memuat data. Silakan refresh halaman.
            </td></tr>`;
        }
    } finally {
        finishLoadingBar();
    }
    
    // Reset checkbox select all
    const selectAll = document.getElementById('selectAllCheckbox');
    if (selectAll) selectAll.checked = false;
    updateSelectedCount();
}

    async function saveKunjungan(event) {
    event.preventDefault();
    
    const id = document.getElementById('rowId').value;
    const submitBtn = document.getElementById('btnSubmit');
    const formData = new FormData();
    
    // ========== VALIDASI AWAL ==========
    // Validasi Hasil Kunjungan
    let hasilKunjungan = document.getElementById('hasilKunjungan').value;
    if (!hasilKunjungan) {
        showMessage('Hasil Kunjungan harus dipilih!', 'error');
        showToast('Hasil Kunjungan harus dipilih!', false);
        return;
    }
    
    if (hasilKunjungan === 'Lainnya') {
        const lainnya = document.getElementById('hasilKunjunganLainnya').value.trim();
        if (!lainnya) {
            showMessage('Silakan isi hasil kunjungan untuk opsi "Lainnya"', 'error');
            showToast('Silakan isi hasil kunjungan untuk opsi "Lainnya"', false);
            document.getElementById('hasilKunjunganLainnya').focus();
            return;
        }
        hasilKunjungan = lainnya;
    }
    
    // ========== TAMPILKAN LOADING ==========
    // Loading di tombol
    if (submitBtn) {
        const originalText = submitBtn.innerHTML;
        submitBtn.setAttribute('data-original-text', originalText);
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        submitBtn.classList.add('btn-loading');
    }
    
    // Progress bar
    startLoadingBar();
    
    // Loading overlay (opsional)
    showLoading('Menyimpan data kunjungan...');
    
    // ========== BANGUN FORM DATA ==========
    formData.append('nama_cabang', document.getElementById('namaCabang').value);
    formData.append('nama_ao', document.getElementById('namaAO').value);
    formData.append('nama_nasabah', document.getElementById('namaNasabah').value);
    formData.append('no_pembiayaan', document.getElementById('noPembiayaan').value);
    formData.append('alamat', document.getElementById('alamat').value);
    
    let tglKunjungan = document.getElementById('tanggalKunjungan').value;
    if (tglKunjungan) {
        formData.append('tanggal_kunjungan', tglKunjungan);
    }
    
    formData.append('keterangan', document.getElementById('keterangan').value);
    formData.append('hasil_kunjungan', hasilKunjungan);
    
    if (id) {
        const currentItem = allData.find(item => item.id == id);
        if (currentItem && currentItem.status === 'rejected') {
            formData.append('status', 'pending');
        }
    }
    
    const foto = document.getElementById('foto').files[0];
    if (foto) {
        // Validasi foto sebelum upload
        if (foto.size > 5 * 1024 * 1024) {
            showMessage('Ukuran foto maksimal 5MB!', 'error');
            showToast('Ukuran foto maksimal 5MB!', false);
            if (submitBtn) {
                submitBtn.innerHTML = submitBtn.getAttribute('data-original-text');
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
            }
            hideLoading();
            finishLoadingBar();
            return;
        }
        formData.append('foto', foto);
    }
    
    // ========== KIRIM KE SERVER ==========
    try {
        const url = id ? `/api/kunjungan/${id}` : '/api/kunjungan';
        if (id) formData.append('_method', 'PUT');
        
        const response = await fetch(url, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include',
            body: formData 
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage(result.message, 'success');
            showToast(result.message, true);
            resetForm();
            loadData();
            
            // Hapus preview foto jika ada
            removeFotoPreview();
        } else {
            showMessage(result.message || 'Gagal menyimpan data', 'error');
            showToast(result.message || 'Gagal menyimpan data', false);
        }
    } catch (error) {
        console.error('Save error:', error);
        showMessage('Terjadi kesalahan: ' + error.message, 'error');
        showToast('Terjadi kesalahan: ' + error.message, false);
    } finally {
        // ========== SEMBUNYIKAN LOADING ==========
        // Kembalikan tombol ke keadaan semula
        if (submitBtn) {
            const originalText = submitBtn.getAttribute('data-original-text');
            if (originalText) {
                submitBtn.innerHTML = originalText;
            } else {
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
            }
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-loading');
        }
        
        // Sembunyikan loading
        hideLoading();
        finishLoadingBar();
    }
}

    function editData(item) {
    if (currentUser.role === 'ao') {
        if (item.status === 'approved') {
            showMessage('Data yang sudah disetujui tidak dapat diedit', 'error');
            showToast('Data yang sudah disetujui tidak dapat diedit', false);
            return;
        }
        if (item.status === 'rejected') {
            showMessage('⚠️ Data ini ditolak. Silakan perbaiki dan simpan kembali.', 'info');
        }
    }
    
    if (currentUser.role !== 'ao') {
        showMessage('Hanya AO yang dapat mengedit data', 'error');
        showToast('Hanya AO yang dapat mengedit data', false);
        return;
    }
    
    let tanggalKunjungan = '';
    if (item.tanggal_kunjungan) {
        if (item.tanggal_kunjungan.includes('T')) {
            tanggalKunjungan = item.tanggal_kunjungan.split('T')[0];
        } else {
            tanggalKunjungan = item.tanggal_kunjungan;
        }
    }
    
    // Isi form dengan data yang ada
    document.getElementById('rowId').value = item.id;
    document.getElementById('namaCabang').value = item.nama_cabang || '';
    document.getElementById('namaAO').value = item.nama_ao || '';
    document.getElementById('namaNasabah').value = item.nama_nasabah || '';
    document.getElementById('noPembiayaan').value = item.no_pembiayaan || '';
    document.getElementById('alamat').value = item.alamat || '';
    document.getElementById('tanggalKunjungan').value = tanggalKunjungan;
    document.getElementById('keterangan').value = item.keterangan || '';
    
    // ========== TAMBAHKAN KODE INI UNTUK HASIL KUNJUNGAN ==========
    const hasilKunjunganSelect = document.getElementById('hasilKunjungan');
    const hasilLainnyaGroup = document.getElementById('hasilLainnyaGroup');
    const hasilLainnyaInput = document.getElementById('hasilKunjunganLainnya');
    
    const hasilKunjungan = item.hasil_kunjungan || '';
    const options = ['Nasabah Ditemui', 'Nasabah Tidak Ditemui', 'Angsuran Lancar', 'Angsuran Bermasalah', 'Restrukturisasi', 'Monitoring', 'Lainnya'];
    
    if (options.includes(hasilKunjungan)) {
        // Jika hasil kunjungan adalah salah satu dari pilihan yang tersedia
        hasilKunjunganSelect.value = hasilKunjungan;
        hasilLainnyaGroup.style.display = 'none';
        hasilLainnyaInput.value = '';
    } else if (hasilKunjungan) {
        // Jika hasil kunjungan adalah teks custom (misal input manual)
        hasilKunjunganSelect.value = 'Lainnya';
        hasilLainnyaGroup.style.display = 'block';
        hasilLainnyaInput.value = hasilKunjungan;
    } else {
        // Jika tidak ada hasil kunjungan
        hasilKunjunganSelect.value = '';
        hasilLainnyaGroup.style.display = 'none';
        hasilLainnyaInput.value = '';
    }
    // ========== SAMPAI SINI ==========
    
    if (item.foto_url) {
        document.getElementById('currentPhotoImg').src = item.foto_url;
        document.getElementById('currentPhoto').style.display = 'block';
    } else {
        document.getElementById('currentPhoto').style.display = 'none';
    }
    
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Data Kunjungan';
    document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-save"></i> Update Data';
    document.getElementById('btnCancel').style.display = 'inline-block';
    document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
}

    function resetForm() {
    document.getElementById('rowId').value = '';
    document.getElementById('kunjunganForm').reset();
    document.getElementById('tanggalKunjungan').value = new Date().toISOString().split('T')[0];
    document.getElementById('currentPhoto').style.display = 'none';
    
    // TAMBAHKAN INI - Reset Hasil Kunjungan
    document.getElementById('hasilKunjungan').value = '';
    document.getElementById('hasilLainnyaGroup').style.display = 'none';
    document.getElementById('hasilKunjunganLainnya').value = '';
    
    if (currentUser?.role === 'ao' && currentUser.cabang) {
        document.getElementById('namaCabang').value = currentUser.cabang;
        document.getElementById('namaCabang').disabled = true;
        document.getElementById('namaAO').value = currentUser.name;
        document.getElementById('namaAO').disabled = true;
    } else {
        document.getElementById('namaCabang').disabled = false;
        document.getElementById('namaAO').disabled = false;
    }
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Tambah Data Kunjungan Baru';
    document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    document.getElementById('btnCancel').style.display = 'none';
}

// ==================== AUTO SAVE DRAFT ====================

let autoSaveTimer = null;
let lastSavedData = null;

// Simpan draft ke localStorage
function saveDraft() {
    const formData = {
        nama_cabang: document.getElementById('namaCabang')?.value || '',
        nama_ao: document.getElementById('namaAO')?.value || '',
        nama_nasabah: document.getElementById('namaNasabah')?.value || '',
        no_pembiayaan: document.getElementById('noPembiayaan')?.value || '',
        alamat: document.getElementById('alamat')?.value || '',
        tanggal_kunjungan: document.getElementById('tanggalKunjungan')?.value || '',
        keterangan: document.getElementById('keterangan')?.value || '',
        hasil_kunjungan: document.getElementById('hasilKunjungan')?.value || '',
        hasil_kunjungan_lainnya: document.getElementById('hasilKunjunganLainnya')?.value || '',
        timestamp: new Date().toISOString()
    };
    
    // Cek apakah ada perubahan
    if (JSON.stringify(formData) === lastSavedData) return;
    
    lastSavedData = JSON.stringify(formData);
    
    // Simpan ke localStorage
    localStorage.setItem('kunjungan_draft', JSON.stringify(formData));
    
    // Tampilkan indikator
    const indicator = document.getElementById('autoSaveIndicator');
    const message = document.getElementById('autoSaveMessage');
    
    if (indicator && message) {
        message.innerHTML = 'Draft tersimpan ' + new Date().toLocaleTimeString();
        indicator.classList.add('show');
        
        setTimeout(() => {
            indicator.classList.remove('show');
        }, 2000);
    }
}

// Load draft dari localStorage
function loadDraft() {
    const savedDraft = localStorage.getItem('kunjungan_draft');
    if (!savedDraft) return false;
    
    const draft = JSON.parse(savedDraft);
    const draftDate = new Date(draft.timestamp);
    const now = new Date();
    const hoursDiff = (now - draftDate) / (1000 * 60 * 60);
    
    // Cek apakah draft masih relevan (kurang dari 24 jam)
    if (hoursDiff > 24) {
        localStorage.removeItem('kunjungan_draft');
        return false;
    }
    
    return draft;
}

// Tampilkan notifikasi draft yang tersimpan
function showDraftNotification() {
    const draft = loadDraft();
    if (!draft) return;
    
    const draftDate = new Date(draft.timestamp);
    const formattedDate = draftDate.toLocaleString('id-ID');
    
    const notif = confirm(
        `Terdapat draft form yang belum selesai!\n\n` +
        `Nasabah: ${draft.nama_nasabah || '-'}\n` +
        `Tanggal: ${draft.tanggal_kunjungan || '-'}\n` +
        `Disimpan pada: ${formattedDate}\n\n` +
        `Apakah Anda ingin melanjutkan mengisi form ini?`
    );
    
    if (notif) {
        // Isi form dengan draft
        document.getElementById('namaCabang').value = draft.nama_cabang || '';
        document.getElementById('namaAO').value = draft.nama_ao || '';
        document.getElementById('namaNasabah').value = draft.nama_nasabah || '';
        document.getElementById('noPembiayaan').value = draft.no_pembiayaan || '';
        document.getElementById('alamat').value = draft.alamat || '';
        document.getElementById('tanggalKunjungan').value = draft.tanggal_kunjungan || '';
        document.getElementById('keterangan').value = draft.keterangan || '';
        
        const hasilSelect = document.getElementById('hasilKunjungan');
        const hasilLainnyaGroup = document.getElementById('hasilLainnyaGroup');
        const hasilLainnyaInput = document.getElementById('hasilKunjunganLainnya');
        
        if (hasilSelect && draft.hasil_kunjungan) {
            const options = ['Nasabah Ditemui', 'Nasabah Tidak Ditemui', 'Angsuran Lancar', 
                           'Angsuran Bermasalah', 'Restrukturisasi', 'Monitoring', 'Lainnya'];
            
            if (options.includes(draft.hasil_kunjungan)) {
                hasilSelect.value = draft.hasil_kunjungan;
                if (hasilLainnyaGroup) hasilLainnyaGroup.style.display = 'none';
            } else if (draft.hasil_kunjungan) {
                hasilSelect.value = 'Lainnya';
                if (hasilLainnyaGroup) hasilLainnyaGroup.style.display = 'block';
                if (hasilLainnyaInput) hasilLainnyaInput.value = draft.hasil_kunjungan;
            }
        }
        
        if (hasilLainnyaInput && draft.hasil_kunjungan_lainnya) {
            hasilLainnyaInput.value = draft.hasil_kunjungan_lainnya;
        }
        
        showToast('Draft berhasil dimuat', true);
    } else {
        // Tanya apakah ingin menghapus draft
        if (confirm('Hapus draft yang tersimpan?')) {
            localStorage.removeItem('kunjungan_draft');
            showToast('Draft dihapus', true);
        }
    }
}

// Hapus draft setelah submit berhasil
function clearDraft() {
    localStorage.removeItem('kunjungan_draft');
    lastSavedData = null;
}

// Auto save setiap 30 detik (dan saat input berubah)
function startAutoSave() {
    // Auto save setiap 30 detik
    if (autoSaveTimer) clearInterval(autoSaveTimer);
    autoSaveTimer = setInterval(() => {
        // Cek apakah form sedang ditampilkan
        const formSection = document.getElementById('formSection');
        if (formSection && formSection.style.display !== 'none') {
            saveDraft();
        }
    }, 30000);
    
    // Auto save saat input berubah
    const formInputs = document.querySelectorAll('#kunjunganForm input, #kunjunganForm select, #kunjunganForm textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            if (autoSaveTimer) {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => saveDraft(), 1000);
            }
        });
    });
}

// Panggil showDraftNotification() saat form section ditampilkan
// Panggil startAutoSave() di DOMContentLoaded


    function searchData() {
        applyFilters();
        const keyword = document.getElementById('searchInput').value.trim();
        if (keyword && filteredData.length === 0) {
            showToast(`Tidak ditemukan data untuk "${keyword}"`, false);
        }
    }

    // ==================== EXPORT FUNCTIONS ====================

// Export ke Excel (Export All)
function exportToExcel() {
    let dataToExport = filteredData.length > 0 ? filteredData : allData;
    
    if (dataToExport.length === 0) {
        showToast('Tidak ada data untuk diexport', false);
        return;
    }
    
    showToast('Sedang memproses Excel...', true);
    
    try {
        // Buat workbook baru
        const wb = XLSX.utils.book_new();
        
        // Data untuk sheet utama
        const mainData = [
            ['LAPORAN DATA KUNJUNGAN NASABAH'],
            ['BPRS AMANAH BANGSA'],
            [''],
            [`Tanggal Export: ${new Date().toLocaleString('id-ID')}`],
            [`Total Data: ${dataToExport.length} kunjungan`],
            [`Disetujui: ${dataToExport.filter(d => d.status === 'approved').length}`],
            [`Pending: ${dataToExport.filter(d => d.status === 'pending').length}`],
            [`Ditolak: ${dataToExport.filter(d => d.status === 'rejected').length}`],
            ['']
        ];
        
        // Header tabel
        const headers = [
            'No', 'Cabang', 'Nama AO', 'Nasabah', 'No Pembiayaan', 
            'Alamat', 'Tanggal Kunjungan', 'Keterangan', 'Hasil Kunjungan', 
            'Waktu Input', 'Status', 'Catatan'
        ];
        
        // Data rows
        const rows = dataToExport.map((item, idx) => [
            idx + 1,
            item.nama_cabang || '',
            item.nama_ao || '',
            item.nama_nasabah || '',
            item.no_pembiayaan || '',
            item.alamat || '',
            item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : '',
            item.keterangan || '-',
            item.hasil_kunjungan || '-',
            item.waktu_input ? new Date(item.waktu_input).toLocaleString('id-ID') : '-',
            item.status === 'approved' ? 'Disetujui' : (item.status === 'rejected' ? 'Ditolak' : 'Pending'),
            item.catatan_manager || '-'
        ]);
        
        // Gabungkan semua data
        const sheetData = [...mainData, headers, ...rows];
        
        // Buat worksheet
        const ws = XLSX.utils.aoa_to_sheet(sheetData);
        
        // Atur lebar kolom
        ws['!cols'] = [
            {wch: 6},   // No
            {wch: 15},  // Cabang
            {wch: 20},  // Nama AO
            {wch: 25},  // Nasabah
            {wch: 18},  // No Pembiayaan
            {wch: 40},  // Alamat
            {wch: 15},  // Tanggal Kunjungan
            {wch: 25},  // Keterangan
            {wch: 25},  // Hasil Kunjungan
            {wch: 20},  // Waktu Input
            {wch: 12},  // Status
            {wch: 35}   // Catatan
        ];
        
        // Style untuk header (opsional - menggunakan cell styling)
        // Untuk baris judul
        for (let i = 0; i < mainData.length; i++) {
            const cellAddress = XLSX.utils.encode_cell({ r: i, c: 0 });
            if (!ws[cellAddress]) ws[cellAddress] = {};
            ws[cellAddress].s = {
                font: { bold: true, sz: 14 },
                alignment: { horizontal: 'center' }
            };
        }
        
        // Style untuk header kolom (baris setelah mainData)
        const headerRow = mainData.length;
        for (let c = 0; c < headers.length; c++) {
            const cellAddress = XLSX.utils.encode_cell({ r: headerRow, c: c });
            if (ws[cellAddress]) {
                ws[cellAddress].s = {
                    font: { bold: true, sz: 11 },
                    fill: { fgColor: { rgb: "4472C4" } },
                    alignment: { horizontal: 'center' }
                };
            }
        }
        
        // Tambahkan worksheet ke workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Data Kunjungan');
        
        // Buat file name
        let filename = `laporan_kunjungan_${new Date().toISOString().split('T')[0]}`;
        if (dateFilterStart || dateFilterEnd) {
            filename += `_${dateFilterStart || 'awal'}_sd_${dateFilterEnd || 'akhir'}`;
        }
        filename += '.xlsx';
        
        // Simpan file
        XLSX.writeFile(wb, filename);
        showToast(`Berhasil export ${dataToExport.length} data ke Excel`, true);
        
    } catch (error) {
        console.error('Export error:', error);
        showToast('Gagal export Excel: ' + error.message, false);
        // Fallback ke CSV jika gagal
        exportToCSV(dataToExport);
    }
}
// Export ke PDF (Export All) - DENGAN FOTO
async function exportToPDF() {
    let dataToExport = filteredData.length > 0 ? filteredData : allData;
    
    if (dataToExport.length === 0) {
        showToast('Tidak ada data untuk diexport', false);
        return;
    }
    
    showToast('Sedang memproses PDF...', true);
    
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        
        // Header dengan logo
        const logoImg = document.querySelector('.logo') || document.querySelector('.login-logo');
        let logoData = null;
        
        if (logoImg && logoImg.src) {
            try {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                logoData = await new Promise((resolve) => {
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        resolve(canvas.toDataURL('image/png'));
                    };
                    img.onerror = () => resolve(null);
                    img.src = logoImg.src;
                });
            } catch(e) {
                console.log('Logo tidak bisa dimuat:', e);
            }
        }
        
        if (logoData) {
            doc.addImage(logoData, 'PNG', 15, 10, 20, 20);
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('BPRS Amanah Bangsa', 40, 18);
            doc.setFontSize(10);
            doc.text('Laporan Data Kunjungan Nasabah', 40, 25);
        } else {
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('BPRS Amanah Bangsa', 15, 18);
            doc.setFontSize(12);
            doc.text('Laporan Data Kunjungan Nasabah', 15, 26);
        }
        
        doc.setFontSize(9);
        doc.text(`Tanggal Export: ${new Date().toLocaleString('id-ID')}`, 15, 35);
        
        if (dateFilterStart || dateFilterEnd) {
            doc.text(`Filter Tanggal: ${dateFilterStart || 'awal'} s/d ${dateFilterEnd || 'akhir'}`, 15, 42);
        }
        
        doc.setLineWidth(0.5);
        doc.line(15, 48, 290, 48);
        
        let yPos = 54;
        doc.setFontSize(9);
        doc.text(`Total Data: ${dataToExport.length} kunjungan`, 15, yPos);
        doc.text(`Disetujui: ${dataToExport.filter(d => d.status === 'approved').length}`, 15, yPos + 5);
        doc.text(`Pending: ${dataToExport.filter(d => d.status === 'pending').length}`, 15, yPos + 10);
        doc.text(`Ditolak: ${dataToExport.filter(d => d.status === 'rejected').length}`, 15, yPos + 15);
        
        // Header tabel
        const headers = [['No', 'Cabang', 'Nama AO', 'Nasabah', 'No Pembiayaan', 'Alamat', 
                         'Tgl Kunjungan', 'Keterangan', 'Hasil Kunjungan', 'Waktu Input', 'Status']];
        
        const rows = dataToExport.map((item, idx) => [
            idx + 1,
            item.nama_cabang || '-',
            item.nama_ao || '-',
            item.nama_nasabah || '-',
            item.no_pembiayaan || '-',
            item.alamat || '-',
            item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : '-',
            item.keterangan || '-',
            item.hasil_kunjungan || '-',
            item.waktu_input ? new Date(item.waktu_input).toLocaleString('id-ID') : '-',
            item.status === 'approved' ? 'Disetujui' : (item.status === 'rejected' ? 'Ditolak' : 'Pending')
        ]);
        
        doc.autoTable({
            head: headers,
            body: rows,
            startY: yPos + 22,
            margin: { left: 10, right: 10 },
            styles: { fontSize: 7, cellPadding: 2, valign: 'top', halign: 'left' },
            headStyles: { fillColor: [102, 126, 234], textColor: [255, 255, 255], fontStyle: 'bold', halign: 'center' },
            alternateRowStyles: { fillColor: [245, 245, 245] },
            columnStyles: {
                0: { halign: 'center', cellWidth: 8 },
                1: { cellWidth: 16 }, 2: { cellWidth: 20 }, 3: { cellWidth: 22 }, 4: { cellWidth: 18 },
                5: { cellWidth: 38 }, 6: { halign: 'center', cellWidth: 16 }, 7: { cellWidth: 20 },
                8: { cellWidth: 22 }, 9: { halign: 'center', cellWidth: 24 }, 10: { halign: 'center', cellWidth: 14 }
            },
            overflow: 'linebreak'
        });
        
        // ========== Kumpulkan Foto untuk Lampiran ==========
        const photos = [];
        for (let idx = 0; idx < dataToExport.length; idx++) {
            const item = dataToExport[idx];
            if (item.foto_url) {
                try {
                    const img = new Image();
                    img.crossOrigin = "Anonymous";
                    const imageData = await new Promise((resolve) => {
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.width;
                            canvas.height = img.height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0);
                            resolve({
                                data: canvas.toDataURL('image/jpeg', 0.7),
                                width: img.width,
                                height: img.height,
                                no: idx + 1,
                                nasabah: item.nama_nasabah,
                                tgl: item.tanggal_kunjungan,
                                hasil: item.hasil_kunjungan
                            });
                        };
                        img.onerror = () => resolve(null);
                        img.src = item.foto_url;
                    });
                    if (imageData) {
                        photos.push(imageData);
                    }
                } catch(e) {
                    console.log('Gambar tidak bisa dimuat:', e);
                }
            }
        }
        
        // ========== Halaman Lampiran Foto ==========
        if (photos.length > 0) {
            doc.addPage();
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('LAMPIRAN FOTO BUKTI KUNJUNGAN', 15, 20);
            doc.setFontSize(9);
            doc.text(`Total ${photos.length} foto`, 15, 28);
            doc.line(15, 32, 290, 32);
            
            let x = 15;
            let y = 40;
            const imageWidth = 85;
            const imageHeight = 70;
            const spacing = 10;
            
            for (let i = 0; i < photos.length; i++) {
                const photo = photos[i];
                try {
                    doc.addImage(photo.data, 'JPEG', x, y, imageWidth, imageHeight);
                    
                    doc.setFontSize(7);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`Foto ${photo.no}`, x + imageWidth/2, y + imageHeight + 4, { align: 'center' });
                    
                    doc.setFontSize(6);
                    doc.setFont('helvetica', 'normal');
                    const nasabahText = photo.nasabah ? photo.nasabah.substring(0, 25) : '-';
                    doc.text(`Nasabah: ${nasabahText}`, x + imageWidth/2, y + imageHeight + 9, { align: 'center' });
                    
                    if (photo.tgl) {
                        const tglText = new Date(photo.tgl).toLocaleDateString('id-ID');
                        doc.text(`Tgl: ${tglText}`, x + imageWidth/2, y + imageHeight + 14, { align: 'center' });
                    }
                    
                    x += imageWidth + spacing;
                    
                    if ((i + 1) % 3 === 0) {
                        x = 15;
                        y += imageHeight + 20;
                    }
                    
                    if (y + imageHeight > doc.internal.pageSize.getHeight() - 20) {
                        doc.addPage();
                        y = 20;
                        x = 15;
                    }
                } catch(e) {
                    console.log('Gagal menambahkan foto:', e);
                }
            }
        } else {
            doc.addPage();
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text('LAMPIRAN FOTO', 15, 20);
            doc.setFontSize(10);
            doc.text('Tidak ada foto bukti kunjungan', 15, 30);
        }
        
        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(150, 150, 150);
            doc.text(`Halaman ${i} dari ${pageCount}`, doc.internal.pageSize.getWidth() - 30, doc.internal.pageSize.getHeight() - 10);
            doc.text('BPRS Amanah Bangsa - Sistem Kunjungan Nasabah', 15, doc.internal.pageSize.getHeight() - 10);
        }
        
        let filename = `laporan_kunjungan_${new Date().toISOString().split('T')[0]}.pdf`;
        if (dateFilterStart || dateFilterEnd) {
            filename = `laporan_kunjungan_${dateFilterStart || 'awal'}_sd_${dateFilterEnd || 'akhir'}.pdf`;
        }
        
        doc.save(filename);
        showToast(`Berhasil export ${dataToExport.length} data ke PDF`, true);
        
    } catch (error) {
        console.error('Error export PDF:', error);
        showToast('Gagal export PDF: ' + error.message, false);
    }
}

// ==================== EXPORT KE WORD (LANDSCAPE) ====================

// Escape HTML untuk keamanan
function escapeHtmlForWord(text) {
    if (!text) return '-';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/\n/g, '<br>');
}

// Export All ke Word (Landscape)
async function exportToWord() {
    let dataToExport = filteredData.length > 0 ? filteredData : allData;
    
    if (dataToExport.length === 0) {
        showToast('Tidak ada data untuk diexport', false);
        return;
    }
    
    showToast('Sedang memproses Word...', true);
    
    try {
        // Ambil logo dengan ukuran kecil
        let logoBase64 = '';
        const logoImg = document.querySelector('.logo') || document.querySelector('.login-logo');
        
        if (logoImg && logoImg.src) {
            try {
                // Buat canvas untuk resize logo
                const img = new Image();
                img.crossOrigin = "Anonymous";
                
                logoBase64 = await new Promise((resolve) => {
                    img.onload = () => {
                        // Resize logo ke ukuran kecil (40px)
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        const maxWidth = 40;
                        const maxHeight = 40;
                        let width = img.width;
                        let height = img.height;
                        
                        if (width > height) {
                            if (width > maxWidth) {
                                height *= maxWidth / width;
                                width = maxWidth;
                            }
                        } else {
                            if (height > maxHeight) {
                                width *= maxHeight / height;
                                height = maxHeight;
                            }
                        }
                        
                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);
                        resolve(canvas.toDataURL('image/png'));
                    };
                    img.onerror = () => resolve(null);
                    img.src = logoImg.src;
                });
            } catch(e) {
                console.log('Logo tidak bisa dimuat:', e);
                logoBase64 = null;
            }
        }
        
        // Hitung statistik
        const totalApproved = dataToExport.filter(d => d.status === 'approved').length;
        const totalPending = dataToExport.filter(d => d.status === 'pending').length;
        const totalRejected = dataToExport.filter(d => d.status === 'rejected').length;
        
        // Buat HTML untuk Word dengan orientasi landscape
        let htmlContent = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan Nasabah</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm 1cm;
        }
        
        body {
            font-family: 'Calibri', 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            line-height: 1.3;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 10px;
        }
        
        .logo-container {
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .title-container {
            display: inline-block;
            vertical-align: middle;
        }
        
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
        }
        
        .report-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
        }
        
        .report-subtitle {
            font-size: 9pt;
            color: #555;
            margin: 0;
        }
        
        /* Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: #f5f5f5;
            font-size: 9pt;
        }
        
        .info-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }
        
        .info-label {
            font-weight: bold;
            background: #e0e0e0;
            width: 15%;
        }
        
        /* Filter Info */
        .filter-info {
            margin: 10px 0;
            padding: 6px 10px;
            background: #e8f4fd;
            border: 1px solid #b8daff;
            font-size: 9pt;
        }
        
        /* Data Table - Scroll horizontal di Word bisa diatur */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-top: 15px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            table-layout: fixed;
        }
        
        .data-table th {
            background: #4472C4;
            color: white;
            padding: 6px 4px;
            border: 1px solid #2c5aa0;
            text-align: center;
            font-weight: bold;
        }
        
        .data-table td {
            border: 1px solid #ddd;
            padding: 5px 4px;
            vertical-align: top;
        }
        
        /* Kolom width */
        .data-table th:nth-child(1), .data-table td:nth-child(1) { width: 4%; text-align: center; }
        .data-table th:nth-child(2), .data-table td:nth-child(2) { width: 8%; }
        .data-table th:nth-child(3), .data-table td:nth-child(3) { width: 10%; }
        .data-table th:nth-child(4), .data-table td:nth-child(4) { width: 12%; }
        .data-table th:nth-child(5), .data-table td:nth-child(5) { width: 10%; }
        .data-table th:nth-child(6), .data-table td:nth-child(6) { width: 15%; }
        .data-table th:nth-child(7), .data-table td:nth-child(7) { width: 8%; text-align: center; }
        .data-table th:nth-child(8), .data-table td:nth-child(8) { width: 10%; }
        .data-table th:nth-child(9), .data-table td:nth-child(9) { width: 10%; }
        .data-table th:nth-child(10), .data-table td:nth-child(10) { width: 8%; text-align: center; }
        .data-table th:nth-child(11), .data-table td:nth-child(11) { width: 7%; text-align: center; }
        
        /* Status Badge */
        .status-approved {
            background: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        /* Signature */
        .signature-table {
            width: 100%;
            margin-top: 25px;
        }
        
        .signature-cell {
            width: 33%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 25px auto 5px auto;
        }
        
        /* Text alignment */
        .text-center {
            text-align: center;
        }
        
        /* Warna statistik */
        .stat-approved { color: #28a745; font-weight: bold; }
        .stat-pending { color: #856404; font-weight: bold; }
        .stat-rejected { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
`;
        
        // Header dengan logo kecil
        htmlContent += `
    <div class="header">
`;
        
        if (logoBase64) {
            htmlContent += `
        <div class="logo-container">
            <img src="${logoBase64}" class="logo" style="width: 40px; height: 40px;">
        </div>
`;
        }
        
        htmlContent += `
        <div class="title-container">
            <div class="company-name">BPRS AMANAH BANGSA</div>
            <div class="report-title">LAPORAN DATA KUNJUNGAN NASABAH</div>
            <div class="report-subtitle">Periode: ${dateFilterStart && dateFilterEnd ? `${dateFilterStart} s/d ${dateFilterEnd}` : 'Semua Data'}</div>
        </div>
    </div>
`;
        
        // Informasi laporan
        htmlContent += `
    <table class="info-table">
        <tr>
            <td class="info-label">Tanggal Export</td>
            <td>${new Date().toLocaleString('id-ID')}</td>
            <td class="info-label">Total Data</td>
            <td><strong>${dataToExport.length}</strong> kunjungan</td>
        </tr>
        <tr>
            <td class="info-label">Disetujui</td>
            <td class="stat-approved">${totalApproved} data</td>
            <td class="info-label">Pending</td>
            <td class="stat-pending">${totalPending} data</td>
        </tr>
        <tr>
            <td class="info-label">Ditolak</td>
            <td class="stat-rejected">${totalRejected} data</td>
            <td class="info-label">User Export</td>
            <td>${currentUser?.name || 'Administrator'}</td>
        </tr>
    </table>
`;
        
        // Filter info
        if (dateFilterStart || dateFilterEnd) {
            htmlContent += `
    <div class="filter-info">
        <strong>📅 Filter Tanggal:</strong> ${dateFilterStart || 'awal'} s/d ${dateFilterEnd || 'akhir'}
    </div>
`;
        }
        
        // Tabel data
        htmlContent += `
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cabang</th>
                    <th>Nama AO</th>
                    <th>Nasabah</th>
                    <th>No Pembiayaan</th>
                    <th>Alamat</th>
                    <th>Tgl Kunjungan</th>
                    <th>Keterangan</th>
                    <th>Hasil Kunjungan</th>
                    <th>Waktu Input</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
`;
        
        for (let idx = 0; idx < dataToExport.length; idx++) {
            const item = dataToExport[idx];
            
            let statusClass = '';
            let statusText = '';
            if (item.status === 'approved') {
                statusClass = 'status-approved';
                statusText = 'Disetujui';
            } else if (item.status === 'rejected') {
                statusClass = 'status-rejected';
                statusText = 'Ditolak';
            } else {
                statusClass = 'status-pending';
                statusText = 'Pending';
            }
            
            // Batasi panjang teks
            const alamat = item.alamat ? (item.alamat.length > 50 ? item.alamat.substring(0, 47) + '...' : item.alamat) : '-';
            const keterangan = item.keterangan ? (item.keterangan.length > 40 ? item.keterangan.substring(0, 37) + '...' : item.keterangan) : '-';
            const hasilKunjungan = item.hasil_kunjungan ? (item.hasil_kunjungan.length > 30 ? item.hasil_kunjungan.substring(0, 27) + '...' : item.hasil_kunjungan) : '-';
            
            htmlContent += `
                <tr>
                    <td class="text-center">${idx + 1}</td>
                    <td>${escapeHtmlForWord(item.nama_cabang || '-')}</td>
                    <td>${escapeHtmlForWord(item.nama_ao || '-')}</td>
                    <td>${escapeHtmlForWord(item.nama_nasabah || '-')}</td>
                    <td>${escapeHtmlForWord(item.no_pembiayaan || '-')}</td>
                    <td>${escapeHtmlForWord(alamat)}</td>
                    <td class="text-center">${item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : '-'}</td>
                    <td>${escapeHtmlForWord(keterangan)}</td>
                    <td>${escapeHtmlForWord(hasilKunjungan)}</td>
                    <td class="text-center">${item.waktu_input ? new Date(item.waktu_input).toLocaleString('id-ID') : '-'}</td>
                    <td class="text-center"><span class="${statusClass}">${statusText}</span></td>
                </tr>
`;
        }
        
        htmlContent += `
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>Dicetak dari Sistem Kunjungan Nasabah BPRS Amanah Bangsa</p>
        <p>&copy; ${new Date().getFullYear()} BPRS Amanah Bangsa. All rights reserved.</p>
        <p><em>Dokumen ini dicetak secara elektronik dan tidak memerlukan tanda tangan basah.</em></p>
    </div>
    
    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                <div class="signature-line"></div>
                <p><strong>Mengetahui,</strong><br>General Manager</p>
            </td>
            <td class="signature-cell">
                <div class="signature-line"></div>
                <p><strong>Petugas,</strong><br>Admin Sistem</p>
            </td>
        </tr>
    </table>
    
    
    
    
    
    
</body>
</html>`;
        
        // Buat nama file dengan ekstensi .doc (Word akan membuka dengan landscape)
        let filename = `laporan_kunjungan_${new Date().toISOString().split('T')[0]}`;
        if (dateFilterStart || dateFilterEnd) {
            filename += `_${dateFilterStart || 'awal'}_sd_${dateFilterEnd || 'akhir'}`;
        }
        filename += '.doc';
        
        // Download sebagai .doc
        const blob = new Blob([htmlContent], { type: 'application/msword' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        showToast(`Berhasil export ${dataToExport.length} data ke Word`, true);
        
    } catch (error) {
        console.error('Error export Word:', error);
        showToast('Gagal export Word: ' + error.message, false);
    }
}

// Export Selected ke Word
async function exportSelectedToWord() {
    const selectedData = getSelectedData();
    
    if (selectedData.length === 0) {
        showToast('Pilih minimal satu data untuk diexport!', false);
        return;
    }
    
    // Simpan data sementara
    const originalFiltered = [...filteredData];
    const originalAllData = [...allData];
    
    filteredData = selectedData;
    allData = selectedData;
    
    await exportToWord();
    
    // Kembalikan data
    filteredData = originalFiltered;
    allData = originalAllData;
    renderTable();
}
// Export Selected Data
async function exportSelected() {
    const selectedData = getSelectedData();
    
    if (selectedData.length === 0) {
        showToast('Pilih minimal satu data untuk diexport!', false);
        return;
    }
    
    const format = confirm('Pilih format export:\n\nOK = Excel\nCancel = PDF');
    
    if (format) {
        exportSelectedToExcel(selectedData);
    } else {
        await exportSelectedToPDF(selectedData);
    }
}

// Export Selected ke Excel
function exportSelectedToExcel(selectedData) {
    if (selectedData.length === 0) {
        showToast('Tidak ada data yang dipilih', false);
        return;
    }
    
    showToast('Sedang memproses Excel...', true);
    
    try {
        const wb = XLSX.utils.book_new();
        
        // Data untuk sheet utama
        const mainData = [
            ['LAPORAN DATA KUNJUNGAN NASABAH (SELECTED)'],
            ['BPRS AMANAH BANGSA'],
            [''],
            [`Tanggal Export: ${new Date().toLocaleString('id-ID')}`],
            [`Total Data Dipilih: ${selectedData.length} kunjungan`],
            [`Disetujui: ${selectedData.filter(d => d.status === 'approved').length}`],
            [`Pending: ${selectedData.filter(d => d.status === 'pending').length}`],
            [`Ditolak: ${selectedData.filter(d => d.status === 'rejected').length}`],
            ['']
        ];
        
        const headers = [
            'No', 'Cabang', 'Nama AO', 'Nasabah', 'No Pembiayaan', 
            'Alamat', 'Tanggal Kunjungan', 'Keterangan', 'Hasil Kunjungan', 
            'Waktu Input', 'Status', 'Catatan'
        ];
        
        const rows = selectedData.map((item, idx) => [
            idx + 1,
            item.nama_cabang || '',
            item.nama_ao || '',
            item.nama_nasabah || '',
            item.no_pembiayaan || '',
            item.alamat || '',
            item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : '',
            item.keterangan || '-',
            item.hasil_kunjungan || '-',
            item.waktu_input ? new Date(item.waktu_input).toLocaleString('id-ID') : '-',
            item.status === 'approved' ? 'Disetujui' : (item.status === 'rejected' ? 'Ditolak' : 'Pending'),
            item.catatan_manager || '-'
        ]);
        
        const sheetData = [...mainData, headers, ...rows];
        const ws = XLSX.utils.aoa_to_sheet(sheetData);
        
        ws['!cols'] = [
            {wch: 6}, {wch: 15}, {wch: 20}, {wch: 25}, {wch: 18},
            {wch: 40}, {wch: 15}, {wch: 25}, {wch: 25}, {wch: 20}, {wch: 12}, {wch: 35}
        ];
        
        XLSX.utils.book_append_sheet(wb, ws, 'Data Kunjungan Selected');
        
        let filename = `laporan_kunjungan_selected_${selectedData.length}_${new Date().toISOString().split('T')[0]}.xlsx`;
        XLSX.writeFile(wb, filename);
        showToast(`Berhasil export ${selectedData.length} data ke Excel`, true);
        
    } catch (error) {
        console.error('Export error:', error);
        showToast('Gagal export Excel: ' + error.message, false);
        exportToCSV(selectedData);
    }
}

// Fallback ke CSV jika Excel gagal
function exportToCSV(dataToExport) {
    const headers = ['No', 'Cabang', 'Nama AO', 'Nasabah', 'No Pembiayaan', 'Alamat', 
                     'Tanggal Kunjungan', 'Keterangan', 'Hasil Kunjungan', 'Waktu Input', 'Status', 'Catatan'];
    
    const rows = dataToExport.map((item, idx) => [
        idx + 1,
        item.nama_cabang || '',
        item.nama_ao || '',
        item.nama_nasabah || '',
        item.no_pembiayaan || '',
        item.alamat || '',
        item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : '',
        item.keterangan || '-',
        item.hasil_kunjungan || '-',
        item.waktu_input ? new Date(item.waktu_input).toLocaleString('id-ID') : '-',
        item.status === 'approved' ? 'Disetujui' : (item.status === 'rejected' ? 'Ditolak' : 'Pending'),
        item.catatan_manager || '-'
    ]);
    
    const csvContent = [headers.join(','), ...rows.map(row => row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(','))].join('\n');
    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `data_kunjungan_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    showToast(`Berhasil export ${dataToExport.length} data ke CSV`, true);
}

// Export Selected ke PDF - DENGAN FOTO
async function exportSelectedToPDF(selectedData) {
    if (selectedData.length === 0) {
        showToast('Tidak ada data yang dipilih', false);
        return;
    }
    
    showToast('Sedang memproses PDF...', true);
    
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        
        // ========== HEADER DENGAN LOGO ==========
        const logoImg = document.querySelector('.logo') || document.querySelector('.login-logo');
        let logoData = null;

        if (logoImg && logoImg.src) {
            try {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                logoData = await new Promise((resolve) => {
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        resolve(canvas.toDataURL('image/png'));
                    };
                    img.onerror = () => resolve(null);
                    img.src = logoImg.src;
                });
            } catch(e) {
                console.log('Logo tidak bisa dimuat:', e);
            }
        }

        if (logoData) {
            doc.addImage(logoData, 'PNG', 15, 10, 20, 20);
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('BPRS Amanah Bangsa', 40, 18);
            doc.setFontSize(10);
            doc.text('Laporan Data Kunjungan Nasabah (Selected)', 40, 25);
        } else {
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('BPRS Amanah Bangsa', 15, 18);
            doc.setFontSize(12);
            doc.text('Laporan Data Kunjungan Nasabah (Selected)', 15, 26);
        }

        doc.setFontSize(9);
        doc.text(`Tanggal Export: ${new Date().toLocaleString('id-ID')}`, 15, 35);
        doc.text(`Total Data Dipilih: ${selectedData.length} kunjungan`, 15, 42);
        // ========== SAMPAI SINI ==========
        
        doc.setLineWidth(0.5);
        doc.line(15, 48, 290, 48);
        
        let yPos = 54;
        doc.text(`Disetujui: ${selectedData.filter(d => d.status === 'approved').length}`, 15, yPos);
        doc.text(`Pending: ${selectedData.filter(d => d.status === 'pending').length}`, 15, yPos + 5);
        doc.text(`Ditolak: ${selectedData.filter(d => d.status === 'rejected').length}`, 15, yPos + 10);
        
        const headers = [['No', 'Cabang', 'Nama AO', 'Nasabah', 'No Pembiayaan', 'Alamat', 
                         'Tgl Kunjungan', 'Keterangan', 'Hasil Kunjungan', 'Waktu Input', 'Status']];
        
        const rows = selectedData.map((item, idx) => [
            idx + 1,
            item.nama_cabang || '-',
            item.nama_ao || '-',
            item.nama_nasabah || '-',
            item.no_pembiayaan || '-',
            item.alamat || '-',
            item.tanggal_kunjungan ? new Date(item.tanggal_kunjungan).toLocaleDateString('id-ID') : '-',
            item.keterangan || '-',
            item.hasil_kunjungan || '-',
            item.waktu_input ? new Date(item.waktu_input).toLocaleString('id-ID') : '-',
            item.status === 'approved' ? 'Disetujui' : (item.status === 'rejected' ? 'Ditolak' : 'Pending')
        ]);
        
        doc.autoTable({
            head: headers,
            body: rows,
            startY: yPos + 22,
            margin: { left: 10, right: 10 },
            styles: { fontSize: 7, cellPadding: 2, valign: 'top', halign: 'left' },
            headStyles: { fillColor: [102, 126, 234], textColor: [255, 255, 255], fontStyle: 'bold', halign: 'center' },
            alternateRowStyles: { fillColor: [245, 245, 245] },
            columnStyles: {
                0: { halign: 'center', cellWidth: 8 },
                1: { cellWidth: 16 }, 2: { cellWidth: 20 }, 3: { cellWidth: 22 }, 4: { cellWidth: 18 },
                5: { cellWidth: 38 }, 6: { halign: 'center', cellWidth: 16 }, 7: { cellWidth: 20 },
                8: { cellWidth: 22 }, 9: { halign: 'center', cellWidth: 24 }, 10: { halign: 'center', cellWidth: 14 }
            },
            overflow: 'linebreak'
        });
        
        // ========== Kumpulkan Foto untuk Lampiran ==========
        const photos = [];
        for (let idx = 0; idx < selectedData.length; idx++) {
            const item = selectedData[idx];
            if (item.foto_url) {
                try {
                    const img = new Image();
                    img.crossOrigin = "Anonymous";
                    const imageData = await new Promise((resolve) => {
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.width;
                            canvas.height = img.height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0);
                            resolve({
                                data: canvas.toDataURL('image/jpeg', 0.7),
                                width: img.width,
                                height: img.height,
                                no: idx + 1,
                                nasabah: item.nama_nasabah,
                                tgl: item.tanggal_kunjungan,
                                hasil: item.hasil_kunjungan
                            });
                        };
                        img.onerror = () => resolve(null);
                        img.src = item.foto_url;
                    });
                    if (imageData) {
                        photos.push(imageData);
                    }
                } catch(e) {
                    console.log('Gambar tidak bisa dimuat:', e);
                }
            }
        }
        
        // ========== Halaman Lampiran Foto ==========
        if (photos.length > 0) {
            doc.addPage();
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('LAMPIRAN FOTO BUKTI KUNJUNGAN', 15, 20);
            doc.setFontSize(9);
            doc.text(`Total ${photos.length} foto`, 15, 28);
            doc.line(15, 32, 290, 32);
            
            let x = 15;
            let y = 40;
            const imageWidth = 85;
            const imageHeight = 70;
            const spacing = 10;
            
            for (let i = 0; i < photos.length; i++) {
                const photo = photos[i];
                try {
                    doc.addImage(photo.data, 'JPEG', x, y, imageWidth, imageHeight);
                    
                    doc.setFontSize(7);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`Foto ${photo.no}`, x + imageWidth/2, y + imageHeight + 4, { align: 'center' });
                    
                    doc.setFontSize(6);
                    doc.setFont('helvetica', 'normal');
                    const nasabahText = photo.nasabah ? photo.nasabah.substring(0, 25) : '-';
                    doc.text(`Nasabah: ${nasabahText}`, x + imageWidth/2, y + imageHeight + 9, { align: 'center' });
                    
                    if (photo.tgl) {
                        const tglText = new Date(photo.tgl).toLocaleDateString('id-ID');
                        doc.text(`Tgl: ${tglText}`, x + imageWidth/2, y + imageHeight + 14, { align: 'center' });
                    }
                    
                    x += imageWidth + spacing;
                    
                    if ((i + 1) % 3 === 0) {
                        x = 15;
                        y += imageHeight + 20;
                    }
                    
                    if (y + imageHeight > doc.internal.pageSize.getHeight() - 20) {
                        doc.addPage();
                        y = 20;
                        x = 15;
                    }
                } catch(e) {
                    console.log('Gagal menambahkan foto:', e);
                }
            }
        } else {
            doc.addPage();
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text('LAMPIRAN FOTO', 15, 20);
            doc.setFontSize(10);
            doc.text('Tidak ada foto bukti kunjungan', 15, 30);
        }
        
        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(150, 150, 150);
            doc.text(`Halaman ${i} dari ${pageCount}`, doc.internal.pageSize.getWidth() - 30, doc.internal.pageSize.getHeight() - 10);
            doc.text('BPRS Amanah Bangsa - Sistem Kunjungan Nasabah', 15, doc.internal.pageSize.getHeight() - 10);
        }
        
        let filename = `laporan_kunjungan_selected_${selectedData.length}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(filename);
        showToast(`Berhasil export ${selectedData.length} data ke PDF`, true);
        
    } catch (error) {
        console.error('Error export PDF:', error);
        showToast('Gagal export PDF: ' + error.message, false);
    }
}

// ========== TAMBAHKAN FUNGSI INI DI SINI ==========
function toggleExportSelectedDropdown() {
    const dropdown = document.getElementById('exportSelectedDropdown');
    if (dropdown) {
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('exportSelectedDropdown');
    const btn = document.getElementById('exportSelectedBtn');
    if (dropdown && btn) {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    }
});
// ========== SAMPAI SINI ==========


    // ==================== USER MANAGEMENT ====================
    
    // Variabel untuk menyimpan state operasi user
let userAction = null; // 'delete' atau 'edit'
let userActionId = null;
let userActionData = null;

// Fungsi untuk membuka modal password sebelum hapus user
function deleteUserWithPassword(id) {
    userAction = 'delete';
    userActionId = id;
    userActionData = null;
    
    document.getElementById('userPasswordModalTitle').textContent = 'Hapus User';
    document.getElementById('userPasswordModalMessage').innerHTML = 'Anda akan menghapus user ini. <strong>Tindakan ini tidak dapat dibatalkan!</strong><br><br>Masukkan password admin untuk melanjutkan.';
    document.getElementById('userPasswordInput').value = '';
    document.getElementById('userPasswordError').style.display = 'none';
    document.getElementById('userPasswordModal').classList.add('show');
    document.getElementById('userPasswordInput').focus();
}

// Fungsi untuk membuka modal password sebelum edit user
function editUserWithPassword(user) {
    userAction = 'edit';
    userActionId = user.id;
    userActionData = user;
    
    document.getElementById('userPasswordModalTitle').textContent = 'Edit User';
    document.getElementById('userPasswordModalMessage').innerHTML = `Anda akan mengedit user <strong>${escapeHtml(user.name)}</strong><br><br>Masukkan password admin untuk melanjutkan.`;
    document.getElementById('userPasswordInput').value = '';
    document.getElementById('userPasswordError').style.display = 'none';
    document.getElementById('userPasswordModal').classList.add('show');
    document.getElementById('userPasswordInput').focus();
}

// Fungsi submit password untuk user operations
async function submitUserPassword() {
    const password = document.getElementById('userPasswordInput').value.trim();
    const errorDiv = document.getElementById('userPasswordError');
    
    if (!password) {
        errorDiv.textContent = 'Password tidak boleh kosong!';
        errorDiv.style.display = 'block';
        return;
    }
    
    // Validasi password
    if (password === '12345') {
        if (userAction === 'delete') {
            await executeDeleteUser(userActionId);
        } else if (userAction === 'edit') {
            openUserModal(userActionData);
        }
        closeUserPasswordModal();
    } else {
        errorDiv.textContent = 'Password salah! Silakan coba lagi.';
        errorDiv.style.display = 'block';
        document.getElementById('userPasswordInput').value = '';
        document.getElementById('userPasswordInput').focus();
    }
}

// Eksekusi hapus user setelah password benar
async function executeDeleteUser(id) {
    try {
        const response = await fetch(`/api/users/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': getCsrfToken(), 
                'Accept': 'application/json' 
            },
            credentials: 'include'
        });
        const result = await response.json();
        if (result.success) {
            showToast(result.message, true);
            loadUsers();
        } else {
            showToast(result.message || 'Gagal menghapus user', false);
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', false);
    }
}

// Fungsi untuk menutup modal password user
function closeUserPasswordModal() {
    document.getElementById('userPasswordModal').classList.remove('show');
    userAction = null;
    userActionId = null;
    userActionData = null;
    document.getElementById('userPasswordInput').value = '';
    document.getElementById('userPasswordError').style.display = 'none';
}

// Handle key press di input password user
function handleUserPasswordKeyPress(event) {
    if (event.key === 'Enter') {
        submitUserPassword();
    }
}

// Toggle visibility password user
function toggleUserPasswordVisibility() {
    const input = document.getElementById('userPasswordInput');
    const icon = document.getElementById('toggleUserPasswordIcon');
    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}
    
    function openUserModal(user = null) {
    const userId = document.getElementById('userId');
    const userFullName = document.getElementById('userFullName');
    const userUsername = document.getElementById('userUsername');
    const userPassword = document.getElementById('userPassword');
    const userRole = document.getElementById('userRole');
    const userCabang = document.getElementById('userCabang');
    const cabangField = document.getElementById('cabangField');
    const cabangBinaanField = document.getElementById('cabangBinaanField');
    const userModalTitle = document.getElementById('userModalTitle');
    
    // Reset checkbox cabang binaan
    document.querySelectorAll('input[name="cabang_binaan"]').forEach(cb => cb.checked = false);
    
    if (userId) userId.value = '';
    if (userPassword) userPassword.value = '';
    
    if (user) {
        // Mode EDIT
        userModalTitle.innerHTML = 'Edit User';
        if (userId) userId.value = user.id;
        if (userFullName) userFullName.value = user.name || '';
        if (userUsername) userUsername.value = user.username || '';
        if (userRole) userRole.value = user.role || 'ao';
        
        // Set cabang untuk AO/Manager
        if (user.role === 'ao' || user.role === 'manager') {
            if (userCabang) userCabang.value = user.cabang || 'Pusat';
            if (cabangField) cabangField.style.display = 'block';
            if (cabangBinaanField) cabangBinaanField.style.display = 'none';
        }
        // Set cabang binaan untuk Supervisor
        else if (user.role === 'supervisor') {
            if (cabangField) cabangField.style.display = 'none';
            if (cabangBinaanField) cabangBinaanField.style.display = 'block';
            
            // Parse cabang_binaan (bisa string JSON atau array)
            let binaanArray = [];
            if (user.cabang_binaan) {
                if (typeof user.cabang_binaan === 'string') {
                    try {
                        binaanArray = JSON.parse(user.cabang_binaan);
                    } catch(e) {
                        binaanArray = user.cabang_binaan.split(',');
                    }
                } else if (Array.isArray(user.cabang_binaan)) {
                    binaanArray = user.cabang_binaan;
                }
            }
            
            // Check checkbox yang sesuai
            document.querySelectorAll('input[name="cabang_binaan"]').forEach(cb => {
                if (binaanArray.includes(cb.value)) {
                    cb.checked = true;
                }
            });
        }
        else {
            // Admin
            if (cabangField) cabangField.style.display = 'none';
            if (cabangBinaanField) cabangBinaanField.style.display = 'none';
        }
    } else {
        // Mode CREATE
        userModalTitle.innerHTML = 'Tambah User Baru';
        if (userRole) userRole.value = 'ao';
        if (userCabang) userCabang.value = 'Pusat';
        if (cabangField) cabangField.style.display = 'block';
        if (cabangBinaanField) cabangBinaanField.style.display = 'none';
        if (userFullName) userFullName.value = '';
        if (userUsername) userUsername.value = '';
    }
    
    document.getElementById('userModal').style.display = 'flex';
}

    function closeUserModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    async function saveUser() {
    const csrfToken = getCsrfToken();
    const id = document.getElementById('userId').value;
    const name = document.getElementById('userFullName').value.trim();
    const username = document.getElementById('userUsername').value.trim();
    const role = document.getElementById('userRole').value;
    const password = document.getElementById('userPassword').value;
    const cabang = document.getElementById('userCabang')?.value;
    
    if (!name || !username) {
        showToast('Nama dan Username harus diisi!', false);
        return;
    }
    
    // Validasi password untuk user baru
    if (!id && (!password || password.length < 4)) {
        showToast('Password minimal 4 karakter untuk user baru!', false);
        return;
    }
    
    // Jika EDIT user (ada id) dan password diisi, validasi password admin
    if (id && password) {
        const adminPassword = prompt('⚠️ VERIFIKASI KEAMANAN\n\nAnda akan mengubah password user. Masukkan password admin untuk melanjutkan:');
        if (adminPassword !== '12345') {
            showToast('Password admin salah! Perubahan password dibatalkan.', false);
            document.getElementById('userPassword').value = '';
            return;
        }
    }
    
    // ========== BUILD DATA OBJECT ==========
    const data = { name, username, role };
    
    if (password) {
        data.password = password;
    }
    
    // ========== HANDLE CABANG BERDASARKAN ROLE ==========
    if (role === 'ao' || role === 'manager') {
        if (!cabang) {
            showToast('Cabang wajib dipilih untuk role AO/Manager!', false);
            return;
        }
        data.cabang = cabang;
    } 
    else if (role === 'supervisor') {
        // Ambil cabang binaan dari checkbox
        const cabangBinaan = [];
        document.querySelectorAll('input[name="cabang_binaan"]:checked').forEach(cb => {
            cabangBinaan.push(cb.value);
        });
        data.cabang_binaan = cabangBinaan;
        data.cabang = null;
    }
    else if (role === 'admin') {
        data.cabang = null;
        data.cabang_binaan = null;
    }
    
    try {
        const response = await fetch(id ? `/api/users/${id}` : '/api/users', {
            method: id ? 'PUT' : 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken, 
                'Accept': 'application/json' 
            },
            credentials: 'include',
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.status === 422) {
            let errorMessage = 'Validasi gagal: ';
            if (result.errors) {
                const errors = Object.values(result.errors).flat();
                errorMessage += errors.join(', ');
            } else {
                errorMessage += result.message || 'Data tidak lengkap';
            }
            showToast(errorMessage, false);
        } 
        else if (result.success) {
            showToast(result.message || 'User berhasil disimpan', true);
            closeUserModal();
            if (currentUser?.role === 'admin') {
                await loadUsers();
            }
        } 
        else {
            showToast(result.message || 'Gagal menyimpan user', false);
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showToast('Terjadi kesalahan: ' + error.message, false);
    }
}

    async function loadUsers() {
    try {
        const response = await fetch('/api/users', {
            headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
            credentials: 'include'
        });
        const result = await response.json();
        if (result.success) {
            window.allUsers = result.data; // Simpan ke global
            renderUserTable(result.data);
        }
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

    function renderUserTable(users) {
    let userSection = document.getElementById('userManagementSection');
    if (!userSection) {
        userSection = document.createElement('div');
        userSection.id = 'userManagementSection';
        userSection.className = 'table-section';
        userSection.style.marginTop = '20px';
        const tableSection = document.querySelector('.table-section');
        if (tableSection && tableSection.parentNode) {
            tableSection.parentNode.insertBefore(userSection, tableSection.nextSibling);
        }
    }
    
    if (!users || users.length === 0) {
        userSection.innerHTML = '<h2><i class="fas fa-users"></i> Manajemen User</h2><p class="text-center">Belum ada user</p>';
        return;
    }
    
    // Simpan data users untuk filtering
    window.allUsers = users;
    
    userSection.innerHTML = `
        <h2><i class="fas fa-users"></i> Manajemen User</h2>
        
        <!-- SEARCH BOX UNTUK USER -->
        <div class="search-box" style="margin-bottom: 15px;">
            <div class="search-wrapper" style="flex: 1;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchUserInput" placeholder="Cari user berdasarkan Nama, Username, Role, atau Cabang..." style="width: 100%; padding: 8px 10px 8px 35px; border: 2px solid #e0e0e0; border-radius: 8px;">
            </div>
            <button id="searchUserBtn" class="btn-primary" style="padding: 8px 16px;">
                <i class="fas fa-search"></i> Cari
            </button>
            <button id="resetUserBtn" class="btn-secondary" style="padding: 8px 16px;">
                <i class="fas fa-sync-alt"></i> Reset
            </button>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Cabang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    ${renderUserTableRows(users)}
                </tbody>
            </table>
        </div>
        <div id="userTableInfo" style="margin-top: 10px; font-size: 12px; color: #666;">
            Menampilkan ${users.length} dari ${window.allUsers.length} user
        </div>
    `;
    
    // Event listener untuk pencarian
    document.getElementById('searchUserBtn')?.addEventListener('click', () => searchUsers());
    document.getElementById('resetUserBtn')?.addEventListener('click', () => resetUserSearch());
    document.getElementById('searchUserInput')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') searchUsers();
    });
}

function renderUserTableRows(users) {
    if (!users || users.length === 0) {
        return '<tr><td colspan="6" style="text-align: center;">Tidak ada user yang ditemukan</td></tr>';
    }
    
    return users.map((user, idx) => {
        let roleBadge = '';
        if (user.role === 'admin') {
            roleBadge = '<span class="role-badge admin"><i class="fas fa-crown"></i> Administrator</span>';
        } else if (user.role === 'manager') {
            roleBadge = '<span class="role-badge manager"><i class="fas fa-chart-line"></i> Manager</span>';
        } else if (user.role === 'supervisor') {
            roleBadge = '<span class="role-badge" style="background: #6f42c1; color: white;"><i class="fas fa-eye"></i> Supervisor</span>';
        } else {
            roleBadge = '<span class="role-badge ao"><i class="fas fa-user-check"></i> Account Officer</span>';
        }
        
        // Tampilkan cabang binaan untuk Supervisor
        let cabangDisplay = user.cabang || '-';
        if (user.role === 'supervisor' && user.cabang_binaan) {
            let binaanArray = [];
            if (typeof user.cabang_binaan === 'string') {
                try {
                    binaanArray = JSON.parse(user.cabang_binaan);
                } catch(e) {
                    binaanArray = user.cabang_binaan.split(',');
                }
            } else if (Array.isArray(user.cabang_binaan)) {
                binaanArray = user.cabang_binaan;
            }
            cabangDisplay = binaanArray.join(', ');
        }
        
        return `
            <tr>
                <td style="text-align: center;">${idx + 1}</td>
                <td>${escapeHtml(user.name)}</td>
                <td>${escapeHtml(user.username)}</td>
                <td>${roleBadge}</td>
                <td>${escapeHtml(cabangDisplay)}</td>
                <td style="text-align: center;">
                    <div class="action-buttons">
                        <button class="action-btn edit" onclick='editUserWithPassword(${JSON.stringify(user).replace(/'/g, "\\'")})'>
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete" onclick="deleteUserWithPassword(${user.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function searchUsers() {
    const keyword = document.getElementById('searchUserInput')?.value.toLowerCase().trim() || '';
    
    if (!keyword) {
        resetUserSearch();
        return;
    }
    
    const filtered = window.allUsers.filter(user => {
        return (
            (user.name && user.name.toLowerCase().includes(keyword)) ||
            (user.username && user.username.toLowerCase().includes(keyword)) ||
            (user.role && user.role.toLowerCase().includes(keyword)) ||
            (user.cabang && user.cabang.toLowerCase().includes(keyword))
        );
    });
    
    const tbody = document.getElementById('userTableBody');
    const info = document.getElementById('userTableInfo');
    
    if (tbody) {
        tbody.innerHTML = renderUserTableRows(filtered);
    }
    if (info) {
        info.innerHTML = `Menampilkan ${filtered.length} dari ${window.allUsers.length} user ${keyword ? `(pencarian: "${keyword}")` : ''}`;
    }
    
    if (filtered.length === 0) {
        showToast(`Tidak ditemukan user dengan kata kunci "${keyword}"`, false);
    }
}

function resetUserSearch() {
    const searchInput = document.getElementById('searchUserInput');
    if (searchInput) searchInput.value = '';
    
    const tbody = document.getElementById('userTableBody');
    const info = document.getElementById('userTableInfo');
    
    if (tbody) {
        tbody.innerHTML = renderUserTableRows(window.allUsers);
    }
    if (info) {
        info.innerHTML = `Menampilkan ${window.allUsers.length} dari ${window.allUsers.length} user`;
    }
}

    // Fungsi deleteUser (dipanggil dari tombol hapus)
function deleteUser(id) {
    deleteUserWithPassword(id);
}

// Fungsi untuk edit user (dipanggil dari tombol edit)
function editUser(user) {
    editUserWithPassword(user);
}

    // ==================== AUTH FUNCTIONS ====================
    async function login(username, password) {
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                credentials: 'include',
                body: JSON.stringify({ username, password })
            });
            const result = await response.json();
            if (result.success) {
                currentUser = result.user;
                updateUIAfterLogin();
                showToast(`Selamat datang, ${currentUser.name}!`, true);
                await loadData();
                if (currentUser.role === 'admin') loadUsers();
                setTimeout(() => window.location.reload(), 100);
                return true;
            } else {
                showLoginMessage(result.message, 'error');
                return false;
            }
        } catch (error) {
            console.error('Login error:', error);
            showLoginMessage('Terjadi kesalahan server', 'error');
            return false;
        }
    }

    function updateUIAfterLogin() {
    document.getElementById('userNameDisplay').textContent = currentUser.name;
    const roleBadge = document.getElementById('userRoleBadge');
    const btnManageUsers = document.getElementById('btnManageUsers');
    const btnLogActivity = document.getElementById('btnLogActivity');
    const formSection = document.getElementById('formSection');
    const statPending = document.getElementById('statPending');
    
    if (currentUser.role === 'admin') {
        roleBadge.innerHTML = ' (Admin)';
        if (btnManageUsers) btnManageUsers.style.display = 'inline-block';
        if (btnLogActivity) btnLogActivity.style.display = 'inline-block';
        if (formSection) formSection.style.display = 'none';
        statPending.style.display = 'block';
    } 
    else if (currentUser.role === 'supervisor') {
    roleBadge.innerHTML = ` (Supervisor)`;
    if (btnManageUsers) btnManageUsers.style.display = 'none';
    if (btnLogActivity) btnLogActivity.style.display = 'inline-block';
    if (formSection) formSection.style.display = 'none';
    statPending.style.display = 'block';
    }
    else if (currentUser.role === 'manager') {
        roleBadge.innerHTML = ` (Manager - ${currentUser.cabang})`;
        if (btnManageUsers) btnManageUsers.style.display = 'none';
        if (btnLogActivity) btnLogActivity.style.display = 'inline-block';
        if (formSection) formSection.style.display = 'none';
        statPending.style.display = 'block';
    } 
    else { // AO
        roleBadge.innerHTML = ` (AO - ${currentUser.cabang})`;
        if (btnManageUsers) btnManageUsers.style.display = 'none';
        if (btnLogActivity) btnLogActivity.style.display = 'none';
        if (formSection) formSection.style.display = 'block';
        statPending.style.display = 'block';
    }
    
    // Start notification polling untuk manager, supervisor, dan admin
    if (currentUser.role === 'manager' || currentUser.role === 'supervisor' || currentUser.role === 'admin') {
        console.log('Starting notification polling for:', currentUser.role);
        startNotificationPolling();
        loadNotificationsFromStorage();
    }
    
    document.getElementById('loginPage').style.display = 'none';
    document.getElementById('mainContent').style.display = 'block';
}

function stopNotificationPolling() {
    if (notificationInterval) {
        clearInterval(notificationInterval);
        notificationInterval = null;
        console.log('Polling stopped');
    }
}

    async function logout() {
    stopNotificationPolling();
    showToast('Logout berhasil!', true);
    try {
        await fetch('/api/logout', { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }, 
            credentials: 'include' 
        });
    } catch (error) {}
    window.location.reload();
}

    function showLoginMessage(message, type) {
        const msgDiv = document.getElementById('loginMessage');
        if (msgDiv) {
            msgDiv.textContent = message;
            msgDiv.className = 'login-message ' + type;
            setTimeout(() => msgDiv.className = 'login-message', 3000);
        }
    }

    // ==================== CHECK AUTH ====================
    async function checkAuth() {
    try {
        const response = await fetch('/api/check-auth?' + Date.now(), {
            headers: { 
                'X-CSRF-TOKEN': getCsrfToken(), 
                'Cache-Control': 'no-cache',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include'
        });
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('Response bukan JSON');
            document.getElementById('loginPage').style.display = 'flex';
            document.getElementById('mainContent').style.display = 'none';
            return;
        }
        
        const result = await response.json();
        
        if (result.authenticated) {
            currentUser = result.user;
            updateUIAfterLogin();
            await loadData();
            if (currentUser.role === 'admin') loadUsers();
        } else {
            document.getElementById('loginPage').style.display = 'flex';
            document.getElementById('mainContent').style.display = 'none';
        }
    } catch (error) {
        console.error('Check auth error:', error);
        document.getElementById('loginPage').style.display = 'flex';
        document.getElementById('mainContent').style.display = 'none';
    }
}
    
    // ==================== LOG ACTIVITY ====================
let currentLogs = [];
let currentLogPage = 1;
let lastLogPage = 1;

function openLogModal() {
    document.getElementById('logModal').style.display = 'flex';
    loadLogs();
}

function closeLogModal() {
    document.getElementById('logModal').style.display = 'none';
}

async function loadLogs() {
    try {
        const search = document.getElementById('logSearch')?.value || '';
        const module = document.getElementById('logModule')?.value || '';
        const action = document.getElementById('logAction')?.value || '';
        const startDate = document.getElementById('logStartDate')?.value || '';
        const endDate = document.getElementById('logEndDate')?.value || '';
        
        let url = `/api/log-activities?page=${currentLogPage}&limit=20`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (module) url += `&module=${module}`;
        if (action) url += `&action=${action}`;
        if (startDate) url += `&start_date=${startDate}`;
        if (endDate) url += `&end_date=${endDate}`;
        
        const response = await fetch(url, {
            headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
            credentials: 'include'
        });
        const result = await response.json();
        
        if (result.success) {
            currentLogs = result.data.data || result.data;
            lastLogPage = result.data.last_page || 1;
            renderLogTable();
            renderLogStats(result.stats);
            updateLogPagination();
        }
    } catch (error) {
        console.error('Error loading logs:', error);
        showToast('Gagal memuat log activity', false);
    }
}

function renderLogTable() {
    const tbody = document.getElementById('logTableBody');
    
    if (!currentLogs || currentLogs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">Tidak ada log activity</td></tr>';
        return;
    }
    
    tbody.innerHTML = currentLogs.map((log, idx) => {
        let actionBadge = '';
        if (log.action === 'CREATE') actionBadge = '<span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-plus"></i> CREATE</span>';
        else if (log.action === 'UPDATE') actionBadge = '<span style="background: #ffc107; color: #333; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-edit"></i> UPDATE</span>';
        else if (log.action === 'DELETE') actionBadge = '<span style="background: #dc3545; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-trash"></i> DELETE</span>';
        else if (log.action === 'APPROVE') actionBadge = '<span style="background: #17a2b8; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-check-circle"></i> APPROVE</span>';
        else if (log.action === 'REJECT') actionBadge = '<span style="background: #fd7e14; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-times-circle"></i> REJECT</span>';
        else actionBadge = `<span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;">${log.action}</span>`;
        
        let moduleBadge = '';
        if (log.module === 'KUNJUNGAN') moduleBadge = '<span style="background: #667eea; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-file-alt"></i> Kunjungan</span>';
        else if (log.module === 'USER') moduleBadge = '<span style="background: #764ba2; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;"><i class="fas fa-users"></i> User</span>';
        else moduleBadge = `<span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px;">${log.module}</span>`;
        
        const date = new Date(log.created_at);
        const formattedDate = date.toLocaleDateString('id-ID', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
        
        let roleBadge = '';
        if (log.user_role === 'admin') roleBadge = '<span style="color: #dc3545; font-weight: bold;"><i class="fas fa-crown"></i> Admin</span>';
        else if (log.user_role === 'manager') roleBadge = '<span style="color: #fd7e14; font-weight: bold;"><i class="fas fa-chart-line"></i> Manager</span>';
        else roleBadge = '<span style="color: #28a745; font-weight: bold;"><i class="fas fa-user-check"></i> AO</span>';
        
        return `
            <tr>
                <td style="text-align: center;">${((currentLogPage-1)*20) + idx + 1}</td>
                <td style="font-size: 11px; white-space: nowrap;">${formattedDate}</td>
                <td><strong>${escapeHtml(log.user_name)}</strong><br><small style="font-size: 10px;">${log.user_cabang || '-'}</small></td>
                <td>${roleBadge}</td>
                <td style="text-align: center;">${actionBadge}</td>
                <td style="text-align: center;">${moduleBadge}</td>
                <td style="font-size: 11px; max-width: 300px;">${escapeHtml(log.description || '-')}</td>
                <td style="font-size: 11px;">${log.ip_address || '-'}</td>
            </tr>
        `;
    }).join('');
}

function renderLogStats(stats) {
    const statsDiv = document.getElementById('logStats');
    if (!statsDiv) return;
    
    statsDiv.innerHTML = `
        <div><i class="fas fa-chart-bar"></i> <strong>Total:</strong> ${stats?.total || 0}</div>
        <div><i class="fas fa-calendar-day"></i> <strong>Hari Ini:</strong> ${stats?.today || 0}</div>
        <div><i class="fas fa-folder"></i> <strong>Per Module:</strong> ${stats?.by_module?.map(m => `${m.module}: ${m.total}`).join(' | ') || '-'}</div>
        <div><i class="fas fa-tasks"></i> <strong>Per Aksi:</strong> ${stats?.by_action?.map(a => `${a.action}: ${a.total}`).join(' | ') || '-'}</div>
    `;
}

function updateLogPagination() {
    document.getElementById('logPageInfo').innerHTML = `Halaman ${currentLogPage} dari ${lastLogPage}`;
    document.getElementById('logPrevPage').onclick = () => { if (currentLogPage > 1) { currentLogPage--; loadLogs(); } };
    document.getElementById('logNextPage').onclick = () => { if (currentLogPage < lastLogPage) { currentLogPage++; loadLogs(); } };
}

function resetLogFilters() {
    document.getElementById('logSearch').value = '';
    document.getElementById('logModule').value = '';
    document.getElementById('logAction').value = '';
    document.getElementById('logStartDate').value = '';
    document.getElementById('logEndDate').value = '';
    currentLogPage = 1;
    loadLogs();
}

async function exportLogs() {
    const startDate = document.getElementById('logStartDate')?.value || '';
    const endDate = document.getElementById('logEndDate')?.value || '';
    
    let url = `/api/log-activities/export/csv`;
    const params = [];
    if (startDate) params.push(`start_date=${startDate}`);
    if (endDate) params.push(`end_date=${endDate}`);
    if (params.length) url += `?${params.join('&')}`;
    
    window.open(url, '_blank');
    showToast('Export log activity sedang diproses', true);
}

// ==================== NOTIFICATION SYSTEM ====================
let notificationInterval = null;
let lastPendingCount = 0;
let notifications = [];
let unreadCount = 0;

// Load notifications from localStorage
function loadNotificationsFromStorage() {
    const saved = localStorage.getItem('notifications');
    if (saved) {
        notifications = JSON.parse(saved);
        updateNotificationBadge();
    }
}

// Save notifications to localStorage
function saveNotificationsToStorage() {
    localStorage.setItem('notifications', JSON.stringify(notifications));
}

// Add notification
function addNotification(title, message, data = null) {
    const notification = {
        id: Date.now(),
        title: title,
        message: message,
        data: data,
        time: new Date().toISOString(),
        read: false
    };
    
    notifications.unshift(notification);
    
    // Keep only last 50 notifications
    if (notifications.length > 50) {
        notifications = notifications.slice(0, 50);
    }
    
    saveNotificationsToStorage();
    updateNotificationBadge();
    renderNotificationList();
    
    // Show toast
    showNotificationToast(title, message);
    
    // Play sound
    playNotificationSound();
}

// Update notification badge
function updateNotificationBadge() {
    unreadCount = notifications.filter(n => !n.read).length;
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        if (unreadCount > 0) {
            badge.style.display = 'inline-block';
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
        } else {
            badge.style.display = 'none';
        }
    }
}

// Render notification list
function renderNotificationList() {
    const container = document.getElementById('notificationList');
    if (!container) return;
    
    if (notifications.length === 0) {
        container.innerHTML = '<div class="notification-empty"><i class="fas fa-bell-slash"></i><br>Tidak ada notifikasi</div>';
        return;
    }
    
    container.innerHTML = notifications.map(notif => `
        <div class="notification-item ${!notif.read ? 'unread' : ''}" onclick="markAsRead(${notif.id})">
            <div class="title">${escapeHtml(notif.title)}</div>
            <div class="message">${escapeHtml(notif.message)}</div>
            <div class="time">${formatNotificationTime(notif.time)}</div>
        </div>
    `).join('');
}

// Format notification time
function formatNotificationTime(isoTime) {
    const date = new Date(isoTime);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Baru saja';
    if (diff < 3600000) return `${Math.floor(diff / 60000)} menit lalu`;
    if (diff < 86400000) return `${Math.floor(diff / 3600000)} jam lalu`;
    return date.toLocaleDateString('id-ID');
}

// Mark as read
function markAsRead(id) {
    const notif = notifications.find(n => n.id === id);
    if (notif) {
        notif.read = true;
        saveNotificationsToStorage();
        updateNotificationBadge();
        renderNotificationList();
        
        // If has data, redirect or show detail
        if (notif.data) {
            // Handle click - bisa redirect ke detail atau refresh data
            loadData();
        }
    }
}

// Mark all as read
function markAllAsRead() {
    notifications.forEach(n => n.read = true);
    saveNotificationsToStorage();
    updateNotificationBadge();
    renderNotificationList();
}

// Toggle notification dropdown
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
        if (dropdown.classList.contains('show')) {
            renderNotificationList();
        }
    }
}

// Close notification dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notificationDropdown');
    const bell = document.getElementById('notificationBell');
    if (dropdown && bell) {
        if (!dropdown.contains(e.target) && !bell.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    }
});

// Show toast notification
function showNotificationToast(title, message) {
    const toast = document.getElementById('toastNotification');
    const toastMessage = document.getElementById('toastMessage');
    
    if (toast && toastMessage) {
        // Custom toast with header
        toast.innerHTML = `
            <div class="toast-header">
                <i class="fas fa-bell" style="margin-right: 8px;"></i>
                <span>${escapeHtml(title)}</span>
                <button class="toast-close" onclick="this.closest('.toast-notification').style.display='none'">&times;</button>
            </div>
            <div class="toast-body">
                <i class="fas fa-info-circle"></i> ${escapeHtml(message)}
            </div>
        `;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 5000);
    }
}

// Play notification sound
function playNotificationSound() {
    // Create audio context
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        
        oscillator.frequency.value = 800;
        gainNode.gain.value = 0.3;
        
        oscillator.start();
        gainNode.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + 0.5);
        oscillator.stop(audioCtx.currentTime + 0.5);
    } catch(e) {
        console.log('Sound not supported');
    }
}

// Check for new pending data (polling)
async function checkNewPendingData() {
    if (!currentUser) return;
    if (currentUser.role !== 'manager' && currentUser.role !== 'admin') return;
    
    try {
        const response = await fetch('/api/kunjungan/pending/count', {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            if (response.status === 401) {
                // Session expired, redirect ke login
                document.getElementById('loginPage').style.display = 'flex';
                document.getElementById('mainContent').style.display = 'none';
                return;
            }
            return;
        }
        
        const result = await response.json();
        
        if (result.success) {
            const pendingCountEl = document.getElementById('pendingCount');
            if (pendingCountEl) {
                pendingCountEl.textContent = result.pending_count;
            }
            
            if (result.new_count > 0) {
                addNotification(
                    'Data Kunjungan Baru',
                    `Terdapat ${result.new_count} data kunjungan baru yang perlu diproses`,
                    { type: 'new_pending', count: result.new_count }
                );
                loadData();
            }
        }
    } catch (error) {
        console.error('Error checking pending data:', error);
    }
}

// Start notification polling
function startNotificationPolling() {
    if (notificationInterval) clearInterval(notificationInterval);
    
    console.log('Polling started - akan cek setiap 15 detik');
    
    // Check every 15 seconds (lebih cepat)
    notificationInterval = setInterval(() => {
        checkNewPendingData();
    }, 15000);
    
    // Langsung cek sekali
    setTimeout(() => {
        checkNewPendingData();
    }, 1000);
}

// Update UI after login - start polling
// Tambahkan di dalam function updateUIAfterLogin()
// Di bagian akhir, panggil:
// startNotificationPolling();
// loadNotificationsFromStorage();

// Dashboard variables
let monthlyChart, statusChart, topAOChart, dailyChart;
let dashboardData = null;

// ========== TAMBAHKAN VARIABEL FILTER DASHBOARD ==========
let dashboardFilters = {
    cabang: 'all',
    ao: 'all',
    status: 'all',  // ← TAMBAHKAN
    start_date: null,
    end_date: null
};
// ========== SAMPAI SINI ==========

// Fungsi untuk load dashboard data dengan filter
// ============ PERBAIKAN FUNGSI LOAD DASHBOARD ============
async function loadDashboard() {
    console.log('loadDashboard dipanggil dengan filters:', dashboardFilters);
    
    // Hanya tampilkan untuk manager, admin, atau supervisor
    if (!currentUser || (currentUser.role !== 'manager' && currentUser.role !== 'admin' && currentUser.role !== 'supervisor')) {
        const dashboardSection = document.getElementById('dashboardSection');
        if (dashboardSection) dashboardSection.style.display = 'none';
        return;
    }
    
    const dashboardSection = document.getElementById('dashboardSection');
    if (dashboardSection) dashboardSection.style.display = 'block';
    
    // Tampilkan loading
    showDashboardLoading();
    
    try {
        // Bangun query string dari filter
        let url = '/api/dashboard/stats?';
        const params = [];
        
        if (dashboardFilters.cabang && dashboardFilters.cabang !== 'all') {
            params.push(`cabang=${encodeURIComponent(dashboardFilters.cabang)}`);
        }
        if (dashboardFilters.ao && dashboardFilters.ao !== 'all') {
            params.push(`ao=${encodeURIComponent(dashboardFilters.ao)}`);
        }
        if (dashboardFilters.status && dashboardFilters.status !== 'all') {
            params.push(`status=${encodeURIComponent(dashboardFilters.status)}`);
        }
        if (dashboardFilters.start_date) {
            params.push(`start_date=${dashboardFilters.start_date}`);
        }
        if (dashboardFilters.end_date) {
            params.push(`end_date=${dashboardFilters.end_date}`);
        }
        
        url += params.join('&');
        console.log('Dashboard API URL:', url);
        
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include'
        });
        
        const result = await response.json();
        console.log('Dashboard API response:', result);
        
        if (result.success) {
            dashboardData = result.data;
            renderSummaryCards(dashboardData.summary);
            renderMonthlyChart(dashboardData.monthly_stats);
            renderStatusChart(dashboardData.status_distribution);
            renderTopAOChart(dashboardData.top_ao);
            renderDailyChart(dashboardData.daily_trend);
            renderCabangStats(dashboardData.cabang_stats);
            
            // Update filter info display
            if (typeof updateFilterInfoDisplay === 'function') {
                updateFilterInfoDisplay();
            }
            
            // Tampilkan pesan sukses dengan jumlah data
            const totalData = dashboardData.summary?.total || 0;
            console.log(`Dashboard loaded: ${totalData} total data`);
        } else {
            console.error('Failed to load dashboard:', result.message);
            if (typeof showToast === 'function') {
                showToast('Gagal memuat data dashboard: ' + (result.message || 'Unknown error'), false);
            }
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
        if (typeof showToast === 'function') {
            showToast('Gagal memuat data dashboard', false);
        }
    } finally {
        hideDashboardLoading();
    }
}

// Fungsi untuk menampilkan loading di chart
function showDashboardLoading() {
    const chartContainers = document.querySelectorAll('#dashboardSection .chart-container');
    chartContainers.forEach(container => {
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
    });
}

function hideDashboardLoading() {
    const chartContainers = document.querySelectorAll('#dashboardSection .chart-container');
    chartContainers.forEach(container => {
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
    });
}

// Fungsi untuk update tampilan info filter
function updateFilterInfoDisplay() {
    const filterInfoDiv = document.getElementById('dashboardFilterInfo');
    const filterInfoText = document.getElementById('dashboardFilterInfoText');
    
    if (!filterInfoDiv || !filterInfoText) return;
    
    const activeFilters = [];
    
    if (dashboardFilters.cabang && dashboardFilters.cabang !== 'all') {
        activeFilters.push(`Cabang: ${dashboardFilters.cabang}`);
    }
    if (dashboardFilters.ao && dashboardFilters.ao !== 'all') {
        activeFilters.push(`AO: ${dashboardFilters.ao}`);
    }
    // ========== TAMBAHKAN INI ==========
    if (dashboardFilters.status && dashboardFilters.status !== 'all') {
        let statusLabel = dashboardFilters.status === 'pending' ? 'Pending' : 
                         (dashboardFilters.status === 'approved' ? 'Disetujui' : 'Ditolak');
        activeFilters.push(`Status: ${statusLabel}`);
    }
    // ========== SAMPAI SINI ==========
    if (dashboardFilters.start_date) {
        activeFilters.push(`Dari: ${formatDate(dashboardFilters.start_date)}`);
    }
    if (dashboardFilters.end_date) {
        activeFilters.push(`Sampai: ${formatDate(dashboardFilters.end_date)}`);
    }
    
    if (activeFilters.length > 0) {
        filterInfoText.innerHTML = `Filter aktif: ${activeFilters.join(' • ')}`;
        filterInfoDiv.style.display = 'block';
    } else {
        filterInfoDiv.style.display = 'none';
    }
}

// Helper format tanggal
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID');
}

// Render summary cards
function renderSummaryCards(summary) {
    const container = document.getElementById('summaryCards');
    if (!container) return;
    
    const approvalRateColor = summary.approval_rate >= 70 ? '#28a745' : (summary.approval_rate >= 40 ? '#ffc107' : '#dc3545');
    
    container.innerHTML = `
        <div class="dashboard-card">
            <div class="card-icon" style="background: #667eea20; color: #667eea;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="card-value">${summary.total.toLocaleString()}</div>
            <div class="card-label">Total Kunjungan</div>
            <div class="card-trend"><i class="fas fa-chart-line"></i> Bulan ini: ${summary.this_month}</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon" style="background: #ffc10720; color: #ffc107;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-value">${summary.pending.toLocaleString()}</div>
            <div class="card-label">Pending</div>
            <div class="card-trend">Menunggu approval</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon" style="background: #28a74520; color: #28a745;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-value">${summary.approved.toLocaleString()}</div>
            <div class="card-label">Disetujui</div>
            <div class="card-trend">${summary.approval_rate}% approval rate</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon" style="background: #dc354520; color: #dc3545;">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="card-value">${summary.rejected.toLocaleString()}</div>
            <div class="card-label">Ditolak</div>
            <div class="card-trend">Perlu perhatian</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon" style="background: #17a2b820; color: #17a2b8;">
                <i class="fas fa-chart-simple"></i>
            </div>
            <div class="card-value">${summary.daily_average}</div>
            <div class="card-label">Rata-rata/Hari</div>
            <div class="card-trend"><i class="fas fa-calendar-day"></i> Hari ini: ${summary.today}</div>
        </div>
    `;
}

// Render monthly chart
function renderMonthlyChart(data) {
    const ctx = document.getElementById('monthlyChart')?.getContext('2d');
    if (!ctx) return;
    
    if (monthlyChart) monthlyChart.destroy();
    
    monthlyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.bulan),
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: data.map(d => d.total),
                backgroundColor: 'rgba(102, 126, 234, 0.7)',
                borderColor: '#667eea',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { callbacks: { label: (ctx) => `${ctx.raw} kunjungan` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'Jumlah' } }
            }
        }
    });
}

// Render status distribution chart (Pie/Doughnut)
function renderStatusChart(data) {
    const ctx = document.getElementById('statusChart')?.getContext('2d');
    if (!ctx) return;
    
    if (statusChart) statusChart.destroy();
    
    const colors = {
        pending: '#ffc107',
        approved: '#28a745',
        rejected: '#dc3545'
    };
    
    statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.map(d => d.label),
            datasets: [{
                data: data.map(d => d.total),
                backgroundColor: data.map(d => colors[d.status] || '#6c757d'),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${ctx.raw/data.reduce((a,b) => a + b.total, 0) * 100}%)` } }
            }
        }
    });
}

// Render top AO chart (Horizontal Bar Chart)
function renderTopAOChart(data) {
    const ctx = document.getElementById('topAOChart')?.getContext('2d');
    if (!ctx) return;
    
    if (topAOChart) topAOChart.destroy();
    
    // Jika tidak ada data
    if (!data || data.length === 0) {
        topAOChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tidak ada data'],
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: [0],
                    backgroundColor: '#ccc'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: () => 'Tidak ada data' } }
                }
            }
        });
        return;
    }
    
    const colors = ['#667eea', '#764ba2', '#28a745', '#ffc107', '#fd7e14'];
    
    topAOChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.nama_ao.length > 15 ? d.nama_ao.substring(0, 12) + '...' : d.nama_ao),
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: data.map(d => d.total),
                backgroundColor: colors.slice(0, data.length),
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: { 
                    callbacks: { 
                        label: (ctx) => `${ctx.raw} kunjungan` 
                    } 
                }
            },
            scales: {
                x: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 }, 
                    title: { display: true, text: 'Jumlah Kunjungan', font: { size: 10 } }
                },
                y: {
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
}

// Render daily trend chart
function renderDailyChart(data) {
    const ctx = document.getElementById('dailyChart')?.getContext('2d');
    if (!ctx) return;
    
    if (dailyChart) dailyChart.destroy();
    
    dailyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.tanggal),
            datasets: [{
                label: 'Kunjungan',
                data: data.map(d => d.total),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#764ba2',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: { callbacks: { label: (ctx) => `${ctx.raw} kunjungan` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, title: { display: true, text: 'Jumlah' } }
            }
        }
    });
}

// Render cabang statistics table
function renderCabangStats(data) {
    const tbody = document.getElementById('cabangStatsBody');
    if (!tbody) return;
    
    const total = data.reduce((sum, item) => sum + item.total, 0);
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(item => {
        const percentage = total > 0 ? ((item.total / total) * 100).toFixed(1) : 0;
        return `
            <tr>
                <td><strong>${escapeHtml(item.cabang)}</strong></td>
                <td>${item.total.toLocaleString()} kunjungan</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="flex: 1; background: #e0e0e0; border-radius: 10px; overflow: hidden;">
                            <div style="width: ${percentage}%; background: linear-gradient(90deg, #667eea, #764ba2); height: 8px; border-radius: 10px;"></div>
                        </div>
                        <span style="min-width: 45px;">${percentage}%</span>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Fungsi untuk mengisi dropdown AO berdasarkan cabang yang dipilih
async function loadAODropdown() {
    const cabang = document.getElementById('dashboardFilterCabang').value;
    const aoSelect = document.getElementById('dashboardFilterAO');
    
    if (!aoSelect) return;
    
    // Reset dropdown
    aoSelect.innerHTML = '<option value="all">Semua AO</option>';
    
    try {
        let url = '/api/users/ao/list';
        if (cabang !== 'all') {
            url += `?cabang=${encodeURIComponent(cabang)}`;
        }
        
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            credentials: 'include'
        });
        
        const result = await response.json();
        
        if (result.success && result.data) {
            result.data.forEach(ao => {
                aoSelect.innerHTML += `<option value="${escapeHtml(ao.name)}">${escapeHtml(ao.name)}${ao.cabang ? ` (${ao.cabang})` : ''}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading AO list:', error);
    }
}

// Fungsi untuk menerapkan filter
// ============ PERBAIKAN FUNGSI APPLY FILTERS ============
function applyDashboardFilters() {
    console.log('applyDashboardFilters dipanggil');
    
    const cabang = document.getElementById('dashboardFilterCabang')?.value || 'all';
    const ao = document.getElementById('dashboardFilterAO')?.value || 'all';
    const status = document.getElementById('dashboardFilterStatus')?.value || 'all';
    const startDate = document.getElementById('dashboardFilterStartDate')?.value || '';
    const endDate = document.getElementById('dashboardFilterEndDate')?.value || '';
    
    console.log('Filter values - Cabang:', cabang, 'AO:', ao, 'Status:', status, 'Start:', startDate, 'End:', endDate);
    
    // Update dashboardFilters
    dashboardFilters = {
        cabang: cabang,
        ao: ao,
        status: status,
        start_date: startDate || null,
        end_date: endDate || null
    };
    
    // Panggil loadDashboard untuk refresh data
    loadDashboard();
    
    // Update info filter display
    if (typeof updateFilterInfoDisplay === 'function') {
        updateFilterInfoDisplay();
    }
}

// ============ PERBAIKAN FUNGSI RESET DASHBOARD FILTERS ============
function resetDashboardFilters() {
    console.log('resetDashboardFilters dipanggil');
    
    // Reset nilai input
    const cabangSelect = document.getElementById('dashboardFilterCabang');
    const aoSelect = document.getElementById('dashboardFilterAO');
    const statusSelect = document.getElementById('dashboardFilterStatus');
    const startDateInput = document.getElementById('dashboardFilterStartDate');
    const endDateInput = document.getElementById('dashboardFilterEndDate');
    
    if (cabangSelect) cabangSelect.value = 'all';
    if (statusSelect) statusSelect.value = 'all';
    if (startDateInput) startDateInput.value = '';
    if (endDateInput) endDateInput.value = '';
    
    // Reset AO dropdown ke semua AO
    if (aoSelect) {
        aoSelect.innerHTML = '<option value="all">Semua AO</option>';
        if (typeof loadAODropdown === 'function') {
            loadAODropdown();
        }
    }
    
    // Reset filter object
    dashboardFilters = {
        cabang: 'all',
        ao: 'all',
        status: 'all',
        start_date: null,
        end_date: null
    };
    
    console.log('Filters reset:', dashboardFilters);
    
    // Load ulang dashboard
    loadDashboard();
    
    if (typeof showToast === 'function') {
        showToast('✅ Semua filter direset, menampilkan semua data', true);
    }
}

// ========== QUICK DATE FUNCTIONS ==========
// ============ PERBAIKAN FUNGSI QUICK DATE ============
function setQuickDate(period) {
    console.log('setQuickDate dipanggil dengan period:', period);
    
    const startDateInput = document.getElementById('dashboardFilterStartDate');
    const endDateInput = document.getElementById('dashboardFilterEndDate');
    const cabangSelect = document.getElementById('dashboardFilterCabang');
    const aoSelect = document.getElementById('dashboardFilterAO');
    const statusSelect = document.getElementById('dashboardFilterStatus');
    
    if (!startDateInput || !endDateInput) {
        console.error('Date input elements not found');
        return;
    }
    
    const today = new Date();
    let startDate = null;
    let endDate = null;
    
    // Reset ke semua cabang dan semua AO terlebih dahulu
    if (cabangSelect) cabangSelect.value = 'all';
    if (aoSelect) aoSelect.innerHTML = '<option value="all">Semua AO</option>';
    if (statusSelect) statusSelect.value = 'all';
    
    // Load ulang AO dropdown berdasarkan cabang 'all'
    if (typeof loadAODropdown === 'function') {
        loadAODropdown();
    }
    
    // Tentukan tanggal berdasarkan period
    if (period === 'today') {
        const dateStr = today.toISOString().split('T')[0];
        startDate = dateStr;
        endDate = dateStr;
        console.log('Filter Hari Ini:', startDate);
    } 
    else if (period === 'yesterday') {
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);
        const dateStr = yesterday.toISOString().split('T')[0];
        startDate = dateStr;
        endDate = dateStr;
        console.log('Filter Kemarin:', startDate);
    }
    else if (period === 'week') {
        // Minggu ini (Senin - Minggu)
        const day = today.getDay();
        const diffToMonday = day === 0 ? 6 : day - 1;
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() - diffToMonday);
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        
        startDate = startOfWeek.toISOString().split('T')[0];
        endDate = endOfWeek.toISOString().split('T')[0];
        console.log('Filter Minggu Ini:', startDate, 's/d', endDate);
    }
    else if (period === 'month') {
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        startDate = startOfMonth.toISOString().split('T')[0];
        endDate = endOfMonth.toISOString().split('T')[0];
        console.log('Filter Bulan Ini:', startDate, 's/d', endDate);
    }
    else if (period === 'lastMonth') {
        const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
        startDate = startOfLastMonth.toISOString().split('T')[0];
        endDate = endOfLastMonth.toISOString().split('T')[0];
        console.log('Filter Bulan Lalu:', startDate, 's/d', endDate);
    }
    else if (period === 'year') {
        startDate = `${today.getFullYear()}-01-01`;
        endDate = `${today.getFullYear()}-12-31`;
        console.log('Filter Tahun Ini:', startDate, 's/d', endDate);
    }
    
    // Set nilai input date
    startDateInput.value = startDate;
    endDateInput.value = endDate;
    
    // Update dashboardFilters
    if (typeof dashboardFilters !== 'undefined') {
        dashboardFilters.cabang = 'all';
        dashboardFilters.ao = 'all';
        dashboardFilters.status = 'all';
        dashboardFilters.start_date = startDate;
        dashboardFilters.end_date = endDate;
        console.log('dashboardFilters updated:', dashboardFilters);
    }
    
    // Terapkan filter dan refresh dashboard
    if (typeof applyDashboardFilters === 'function') {
        applyDashboardFilters();
    } else if (typeof loadDashboard === 'function') {
        loadDashboard();
    }
    
    // Tampilkan notifikasi
    let periodText = '';
    switch(period) {
        case 'today': periodText = 'Hari Ini'; break;
        case 'yesterday': periodText = 'Kemarin'; break;
        case 'week': periodText = 'Minggu Ini'; break;
        case 'month': periodText = 'Bulan Ini'; break;
        case 'lastMonth': periodText = 'Bulan Lalu'; break;
        case 'year': periodText = 'Tahun Ini'; break;
    }
    
    if (typeof showToast === 'function') {
        showToast(`✅ Menampilkan data ${periodText} untuk SEMUA cabang`, true);
    }
}

// ============ INISIALISASI DEFAULT DATE UNTUK DASHBOARD ============
function initDashboardDates() {
    const startDateInput = document.getElementById('dashboardFilterStartDate');
    const endDateInput = document.getElementById('dashboardFilterEndDate');
    
    if (startDateInput && endDateInput) {
        // Set default: 30 hari terakhir
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 30);
        
        startDateInput.value = startDate.toISOString().split('T')[0];
        endDateInput.value = endDate.toISOString().split('T')[0];
        
        // Set ke dashboardFilters jika ada
        if (typeof dashboardFilters !== 'undefined') {
            dashboardFilters.start_date = startDateInput.value;
            dashboardFilters.end_date = endDateInput.value;
        }
    }
}

// Panggil inisialisasi saat halaman dimuat
// Tambahkan di dalam DOMContentLoaded:
// initDashboardDates();

    // ==================== EVENT LISTENERS ====================
    document.addEventListener('DOMContentLoaded', function() {
        // Login form
        document.getElementById('loginForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            login(document.getElementById('loginUsername').value, document.getElementById('loginPassword').value);
        });
        
        // Form kunjungan
        document.getElementById('kunjunganForm')?.addEventListener('submit', saveKunjungan);
        document.getElementById('btnCancel')?.addEventListener('click', resetForm);
        
        // Search buttons
        document.getElementById('searchButton')?.addEventListener('click', searchData);
        document.getElementById('resetButton')?.addEventListener('click', () => { 
            document.getElementById('searchInput').value = ''; 
            clearAllFilters(); // Ubah dari clearDateFilter menjadi clearAllFilters
        });
        document.getElementById('refreshButton')?.addEventListener('click', loadData);
        
        // Pagination
        document.getElementById('prevPage')?.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
        document.getElementById('nextPage')?.addEventListener('click', () => { if (currentPage * rowsPerPage < filteredData.length) { currentPage++; renderTable(); } });
        
        // Search input enter key
        document.getElementById('searchInput')?.addEventListener('keypress', (e) => { if (e.key === 'Enter') searchData(); });
        
        // Loading indicators
        document.querySelectorAll('a, button[onclick]').forEach(el => {
            if (el.getAttribute('href') && !el.getAttribute('href').startsWith('#')) {
                el.addEventListener('click', () => {
                    if (!el.classList.contains('no-loading')) {
                        startLoadingBar();
                    }
                });
            }
        });
        
        // Form submit loading
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                startLoadingBar();
            });
        });
        
        // ========== DASHBOARD FILTER EVENT LISTENERS ==========
        const applyFilterBtn = document.getElementById('dashboardApplyFilter');
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', applyDashboardFilters);
        }
        
        const resetFilterBtn = document.getElementById('dashboardResetFilter');
        if (resetFilterBtn) {
            resetFilterBtn.addEventListener('click', resetDashboardFilters);
        }
        
        const cabangFilter = document.getElementById('dashboardFilterCabang');
        if (cabangFilter) {
            cabangFilter.addEventListener('change', function() {
                loadAODropdown();
            });
        }
        
        // Set default tanggal (1 bulan terakhir)
        const startDateInput = document.getElementById('dashboardFilterStartDate');
        const endDateInput = document.getElementById('dashboardFilterEndDate');
        if (startDateInput && endDateInput) {
            const oneMonthAgo = new Date();
            oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
            const today = new Date();
            
            startDateInput.value = oneMonthAgo.toISOString().split('T')[0];
            endDateInput.value = today.toISOString().split('T')[0];
        }
        
        loadAODropdown();
        
        // ========== EVENT LISTENER UNTUK HASIL KUNJUNGAN ==========
        const hasilKunjunganSelect = document.getElementById('hasilKunjungan');
        const hasilLainnyaGroup = document.getElementById('hasilLainnyaGroup');
        
        if (hasilKunjunganSelect && hasilLainnyaGroup) {
            if (hasilKunjunganSelect.value === 'Lainnya') {
                hasilLainnyaGroup.style.display = 'block';
            } else {
                hasilLainnyaGroup.style.display = 'none';
            }
            
            hasilKunjunganSelect.addEventListener('change', function() {
                if (this.value === 'Lainnya') {
                    hasilLainnyaGroup.style.display = 'block';
                    const inputLainnya = document.getElementById('hasilKunjunganLainnya');
                    if (inputLainnya) inputLainnya.focus();
                } else {
                    hasilLainnyaGroup.style.display = 'none';
                    const inputLainnya = document.getElementById('hasilKunjunganLainnya');
                    if (inputLainnya) inputLainnya.value = '';
                }
            });
        }
        
        // ========== PREVIEW FOTO SEBELUM UPLOAD ==========
        document.getElementById('foto')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Ukuran foto maksimal 5MB!', false);
                    this.value = '';
                    return;
                }
                
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    showToast('Format foto harus JPG, JPEG, atau PNG!', false);
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewContainer = document.getElementById('fotoPreviewContainer');
                    const previewImg = document.getElementById('fotoPreviewImg');
                    const previewName = document.getElementById('fotoPreviewName');
                    const previewSize = document.getElementById('fotoPreviewSize');
                    
                    previewImg.src = event.target.result;
                    previewName.textContent = file.name;
                    previewSize.textContent = ` (${(file.size / 1024).toFixed(1)} KB)`;
                    previewContainer.classList.add('show');
                };
                reader.readAsDataURL(file);
            }
        });
        
        // ========== EXPORT DROPDOWN ==========
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.export-dropdown');
            const exportBtn = document.getElementById('exportButton');
            const exportContent = document.getElementById('exportDropdown');
            if (exportBtn && exportContent && dropdown) {
                if (e.target === exportBtn || exportBtn.contains(e.target)) {
                    e.stopPropagation();
                    exportContent.style.display = exportContent.style.display === 'block' ? 'none' : 'block';
                } else if (!dropdown.contains(e.target)) {
                    exportContent.style.display = 'none';
                }
            }
        });
        
        // Set default filter date range (current month)
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        document.getElementById('filterStartDate').value = firstDay.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = lastDay.toISOString().split('T')[0];
        
        // ========== TAMBAHKAN KODE NO 7 DI SINI ==========
        // Event listener untuk filter status pada tabel kunjungan
        const filterStatus = document.getElementById('filterStatus');
        if (filterStatus) {
            filterStatus.addEventListener('change', function() {
                filterByStatus();
            });
        }
        // ========== SAMPAI SINI ==========
        
        // Check authentication
        checkAuth();
    });
    </script>
    
<!-- ==================== CHAT BOX AI ASSISTANT (DENGAN DRAG & DROP - SUPPORT MOBILE) ==================== -->
<style>
    /* Chat Button - Posisi Awal Kanan Bawah, Bisa di-drag */
    .chat-button {
        position: fixed !important;
        bottom: 25px;
        right: 25px;
        left: auto;
        top: auto;
        width: auto !important;
        min-width: 160px !important;
        height: 56px !important;
        border-radius: 50px !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border: none !important;
        cursor: grab !important;
        box-shadow: 0 8px 25px rgba(102,126,234,0.4) !important;
        z-index: 999999 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 12px !important;
        padding: 0 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        letter-spacing: 0.5px !important;
        overflow: hidden !important;
        margin: 0 !important;
        transition: box-shadow 0.2s, transform 0.1s, opacity 0.2s !important;
        user-select: none !important;
        -webkit-tap-highlight-color: transparent !important;
        touch-action: none !important; /* Mencegah scroll saat drag di mobile */
    }
    
    .chat-button:active {
        cursor: grabbing !important;
    }
    
    /* Sembunyikan saat logout */
    .chat-button.hide-chat,
    .chat-window.hide-chat {
        display: none !important;
    }
    
    .chat-button:hover {
        transform: translateY(-3px) scale(1.03) !important;
        box-shadow: 0 15px 40px rgba(102,126,234,0.6) !important;
    }
    
    .chat-button i:first-child {
        font-size: 24px !important;
        animation: iconWave 1s ease-in-out infinite !important;
        pointer-events: none !important;
    }
    
    @keyframes iconWave {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        75% { transform: rotate(-15deg); }
    }
    
    .chat-button .button-text {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: white !important;
        pointer-events: none !important;
    }
    
    .chat-button .chat-badge {
        position: absolute !important;
        top: -8px !important;
        right: -8px !important;
        background: #ff4757 !important;
        color: white !important;
        font-size: 11px !important;
        font-weight: bold !important;
        padding: 4px 9px !important;
        border-radius: 30px !important;
        animation: badgePulse 1s infinite !important;
        z-index: 999999 !important;
        pointer-events: none !important;
    }
    
    @keyframes badgePulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .chat-button .chevron {
        font-size: 12px !important;
        transition: transform 0.3s ease !important;
        margin-left: 5px !important;
        pointer-events: none !important;
    }
    
    /* Chat Window - Bisa di-drag dari header */
    .chat-window {
        position: fixed !important;
        bottom: 100px;
        right: 25px;
        left: auto;
        top: auto;
        width: 420px !important;
        height: 580px !important;
        background: white !important;
        border-radius: 28px !important;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3) !important;
        z-index: 999998 !important;
        display: none !important;
        flex-direction: column !important;
        overflow: hidden !important;
        border: 1px solid rgba(102,126,234,0.3) !important;
        margin: 0 !important;
        transition: box-shadow 0.2s, opacity 0.2s !important;
    }
    
    .chat-window.dragging-window {
        opacity: 0.95 !important;
        cursor: grabbing !important;
    }
    
    .chat-window.open {
        display: flex !important;
        animation: slideUpChat 0.4s ease !important;
    }
    
    @keyframes slideUpChat {
        from { opacity: 0; transform: translateY(50px) scale(0.9); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        padding: 18px 20px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        cursor: grab !important;
        user-select: none !important;
        -webkit-tap-highlight-color: transparent !important;
        touch-action: none !important; /* Mencegah scroll saat drag di mobile */
    }
    
    .chat-header:active {
        cursor: grabbing !important;
    }
    
    .chat-header-left {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        pointer-events: none !important;
    }
    
    .chat-avatar {
        width: 48px !important;
        height: 48px !important;
        background: rgba(255,255,255,0.2) !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 26px !important;
    }
    
    .chat-header-info h3 {
        margin: 0 !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        color: white !important;
    }
    
    .chat-header-info p {
        margin: 4px 0 0 !important;
        font-size: 11px !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        color: white !important;
    }
    
    .online-dot {
        width: 8px !important;
        height: 8px !important;
        background: #2ed573 !important;
        border-radius: 50% !important;
        display: inline-block !important;
        animation: blinkDot 1s infinite !important;
    }
    
    @keyframes blinkDot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .chat-header button {
        background: rgba(255,255,255,0.2) !important;
        border: none !important;
        color: white !important;
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        cursor: pointer !important;
        font-size: 16px !important;
        z-index: 10 !important;
        position: relative !important;
        transition: all 0.2s !important;
    }
    
    .chat-header button:hover {
        background: rgba(255,255,255,0.4) !important;
        transform: scale(1.05);
    }
    
    .chat-messages {
        flex: 1 !important;
        overflow-y: auto !important;
        padding: 20px !important;
        background: #f8f9fc !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 16px !important;
    }
    
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .chat-messages::-webkit-scrollbar-track {
        background: #e8e8e8;
        border-radius: 10px;
    }
    
    .chat-messages::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }
    
    .message {
        display: flex !important;
        gap: 12px !important;
    }
    
    .message.bot {
        justify-content: flex-start !important;
    }
    
    .message.user {
        justify-content: flex-end !important;
    }
    
    .message-avatar {
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 18px !important;
        flex-shrink: 0 !important;
    }
    
    .message.bot .message-avatar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }
    
    .message.user .message-avatar {
        background: #2ed573 !important;
        color: white !important;
    }
    
    .message-content {
        max-width: 70% !important;
        padding: 12px 16px !important;
        border-radius: 20px !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
    }
    
    .message.bot .message-content {
        background: white !important;
        color: #333 !important;
        border-bottom-left-radius: 4px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
    }
    
    .message.user .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-bottom-right-radius: 4px !important;
    }
    
    .message-time {
        font-size: 10px !important;
        color: #999 !important;
        margin-top: 6px !important;
        display: block !important;
    }
    
    .chat-typing {
        padding: 10px 16px !important;
        background: white !important;
        border-radius: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        font-size: 12px !important;
        color: #666 !important;
    }
    
    .chat-typing span {
        width: 8px !important;
        height: 8px !important;
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        border-radius: 50% !important;
        display: inline-block !important;
        animation: typingAnim 1.4s infinite !important;
    }
    
    .chat-typing span:nth-child(2) { animation-delay: 0.2s; }
    .chat-typing span:nth-child(3) { animation-delay: 0.4s; }
    
    @keyframes typingAnim {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-8px); opacity: 1; }
    }
    
    .chat-suggestions {
        padding: 12px 15px !important;
        background: white !important;
        border-top: 1px solid #f0f0f0 !important;
        border-bottom: 1px solid #f0f0f0 !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
    }
    
    .suggestion-chip {
        background: #f0f2f8 !important;
        padding: 7px 15px !important;
        border-radius: 25px !important;
        font-size: 11px !important;
        cursor: pointer !important;
        color: #667eea !important;
        font-weight: 500 !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        transition: all 0.2s !important;
        -webkit-tap-highlight-color: transparent !important;
    }
    
    .suggestion-chip:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        transform: scale(1.02);
    }
    
    .suggestion-chip:active {
        transform: scale(0.98);
    }
    
    .chat-input-area {
        padding: 15px !important;
        background: white !important;
        display: flex !important;
        gap: 12px !important;
        border-top: 1px solid #f0f0f0 !important;
    }
    
    .chat-input-area input {
        flex: 1 !important;
        padding: 12px 18px !important;
        border: 2px solid #e8e8f0 !important;
        border-radius: 30px !important;
        font-size: 13px !important;
        outline: none !important;
        transition: all 0.2s !important;
    }
    
    .chat-input-area input:focus {
        border-color: #667eea !important;
    }
    
    .chat-input-area button {
        width: 46px !important;
        height: 46px !important;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        -webkit-tap-highlight-color: transparent !important;
    }
    
    .chat-input-area button:hover {
        transform: scale(1.05) !important;
    }
    
    .chat-input-area button:active {
        transform: scale(0.95) !important;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #667eea12 0%, #764ba212 100%) !important;
        border-radius: 16px !important;
        padding: 14px !important;
        margin-bottom: 12px !important;
        border-left: 4px solid #667eea !important;
    }
    
    .welcome-banner .title {
        font-weight: 700 !important;
        margin-bottom: 8px !important;
        color: #667eea !important;
        font-size: 13px !important;
    }
    
    /* Responsive Mobile */
    @media (max-width: 480px) {
        .chat-button {
            min-width: 130px !important;
            height: 50px !important;
            padding: 0 18px !important;
            bottom: 15px !important;
            right: 15px !important;
        }
        .chat-button .button-text { font-size: 12px !important; }
        .chat-window {
            width: calc(100% - 30px) !important;
            right: 15px !important;
            height: 520px !important;
            bottom: 85px !important;
        }
        .message-content {
            max-width: 85% !important;
            font-size: 12px !important;
        }
        .suggestion-chip {
            font-size: 10px !important;
            padding: 6px 12px !important;
        }
    }
    
    /* Untuk mobile, beri feedback touch */
    @media (max-width: 768px) {
        .chat-button:active {
            opacity: 0.8 !important;
            transform: scale(0.98) !important;
        }
        .chat-header:active {
            opacity: 0.95 !important;
        }
    }
</style>

<!-- HTML Chat Button -->
<button class="chat-button hide-chat" id="chatButton">
    <i class="fas fa-comment-dots"></i>
    <span class="button-text">AI Assistant</span>
    <i class="fas fa-chevron-down chevron"></i>
    <span class="chat-badge" id="chatBadge" style="display: none;">!</span>
</button>

<!-- Chat Window -->
<div class="chat-window hide-chat" id="chatWindow">
    <div class="chat-header" id="chatHeaderDrag">
        <div class="chat-header-left">
            <div class="chat-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chat-header-info">
                <h3>AI Assistant BPRS</h3>
                <p><span class="online-dot"></span> Online • Siap Membantu</p>
            </div>
        </div>
        <button id="closeChatBtn"><i class="fas fa-times"></i></button>
    </div>
    
    <div class="chat-messages" id="chatMessages">
        <div class="message bot">
            <div class="message-avatar"><i class="fas fa-robot"></i></div>
            <div class="message-content">
                <div class="welcome-banner">
                    <div class="title">🤖 Halo! Saya AI Assistant BPRS Amanah Bangsa</div>
                    <div>Saya siap membantu Anda menggunakan sistem kunjungan nasabah!</div>
                </div>
                <strong>📋 Yang bisa saya bantu:</strong><br>
                • Cara input data kunjungan<br>
                • Proses approve/reject data<br>
                • Export laporan (Excel/PDF/Word)<br>
                • Status dan role user<br>
                • Fitur notifikasi & reminder<br>
                • Dan masih banyak lagi!<br><br>
                💡 Coba tanyakan: "Cara mengisi data?" atau "Bagaimana approve?"<br><br>
                ✨ <strong>Tips:</strong> Anda bisa drag chat window ini dari header untuk memindahkannya (support mobile)!
                <span class="message-time">Sekarang</span>
            </div>
        </div>
    </div>
    
    <div class="chat-suggestions">
        <div class="suggestion-chip" onclick="sendSuggestion('Cara mengisi data kunjungan?')"><i class="fas fa-pen-alt"></i> Cara isi data?</div>
        <div class="suggestion-chip" onclick="sendSuggestion('Bagaimana cara approve data?')"><i class="fas fa-check-circle"></i> Cara approve?</div>
        <div class="suggestion-chip" onclick="sendSuggestion('Cara export ke Excel')"><i class="fas fa-file-excel"></i> Export Excel?</div>
        <div class="suggestion-chip" onclick="sendSuggestion('Apa itu status pending?')"><i class="fas fa-clock"></i> Status pending?</div>
        <div class="suggestion-chip" onclick="sendSuggestion('Fitur reminder banner')"><i class="fas fa-bell"></i> Fitur reminder?</div>
    </div>
    
    <div class="chat-input-area">
        <input type="text" id="chatInput" placeholder="Ketik pertanyaan Anda disini..." onkeypress="handleChatKeyPress(event)">
        <button id="chatSendBtn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
// ==================== CHAT BOX AI ASSISTANT DENGAN DRAG & DROP (SUPPORT MOBILE) ====================

// Variabel untuk drag button
var chatButtonDrag = {
    isDragging: false,
    startX: 0,
    startY: 0,
    initialLeft: null,
    initialTop: null,
    initialRight: null,
    initialBottom: null
};

// Variabel untuk drag window
var chatWindowDrag = {
    isDragging: false,
    startX: 0,
    startY: 0,
    initialLeft: null,
    initialTop: null,
    initialRight: null,
    initialBottom: null
};

// Inisialisasi drag & drop (Support Desktop + Mobile)
function initDragAndDrop() {
    var chatButton = document.getElementById('chatButton');
    var chatWindow = document.getElementById('chatWindow');
    var chatHeader = document.getElementById('chatHeaderDrag');
    
    if (!chatButton || !chatWindow || !chatHeader) return;
    
    // ============ DRAG UNTUK CHAT BUTTON (Desktop + Mobile) ============
    function startDragButton(e) {
        // Cegah jika klik pada badge
        if (e.target.classList && e.target.classList.contains('chat-badge')) return;
        
        e.preventDefault();
        
        // Dapatkan koordinat (support mouse dan touch)
        var clientX = e.clientX;
        var clientY = e.clientY;
        if (e.touches) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }
        
        var rect = chatButton.getBoundingClientRect();
        var computedStyle = window.getComputedStyle(chatButton);
        
        chatButtonDrag.startX = clientX;
        chatButtonDrag.startY = clientY;
        chatButtonDrag.initialLeft = parseFloat(computedStyle.left);
        chatButtonDrag.initialTop = parseFloat(computedStyle.top);
        chatButtonDrag.isDragging = true;
        
        // Ubah style untuk drag
        chatButton.style.cursor = 'grabbing';
        chatButton.style.transition = 'none';
        chatButton.style.opacity = '0.8';
        
        // Register event listeners
        document.addEventListener('mousemove', onChatButtonMove);
        document.addEventListener('mouseup', onChatButtonEnd);
        document.addEventListener('touchmove', onChatButtonMove, { passive: false });
        document.addEventListener('touchend', onChatButtonEnd);
    }
    
    function onChatButtonMove(e) {
        if (!chatButtonDrag.isDragging) return;
        e.preventDefault();
        
        // Dapatkan koordinat (support mouse dan touch)
        var clientX = e.clientX;
        var clientY = e.clientY;
        if (e.touches) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }
        
        var dx = clientX - chatButtonDrag.startX;
        var dy = clientY - chatButtonDrag.startY;
        
        var newLeft = chatButtonDrag.initialLeft + dx;
        var newTop = chatButtonDrag.initialTop + dy;
        
        // Batasi agar tidak keluar viewport
        var buttonRect = chatButton.getBoundingClientRect();
        var maxX = window.innerWidth - buttonRect.width - 10;
        var maxY = window.innerHeight - buttonRect.height - 10;
        var minX = 10;
        var minY = 10;
        
        newLeft = Math.min(maxX, Math.max(minX, newLeft));
        newTop = Math.min(maxY, Math.max(minY, newTop));
        
        chatButton.style.left = newLeft + 'px';
        chatButton.style.top = newTop + 'px';
        chatButton.style.right = 'auto';
        chatButton.style.bottom = 'auto';
    }
    
    function onChatButtonEnd() {
        if (!chatButtonDrag.isDragging) return;
        
        chatButtonDrag.isDragging = false;
        chatButton.style.cursor = 'grab';
        chatButton.style.transition = '';
        chatButton.style.opacity = '1';
        
        // Simpan posisi ke localStorage
        var left = chatButton.style.left;
        var top = chatButton.style.top;
        if (left && top && left !== 'auto' && top !== 'auto') {
            localStorage.setItem('chatButtonLeft', left);
            localStorage.setItem('chatButtonTop', top);
        }
        
        // Cleanup event listeners
        document.removeEventListener('mousemove', onChatButtonMove);
        document.removeEventListener('mouseup', onChatButtonEnd);
        document.removeEventListener('touchmove', onChatButtonMove);
        document.removeEventListener('touchend', onChatButtonEnd);
    }
    
    chatButton.addEventListener('mousedown', startDragButton);
    chatButton.addEventListener('touchstart', startDragButton, { passive: false });
    
    // ============ DRAG UNTUK CHAT WINDOW (melalui header) (Desktop + Mobile) ============
    function startDragWindow(e) {
        // Jangan drag jika klik pada tombol close
        if (e.target.closest && e.target.closest('#closeChatBtn')) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        // Dapatkan koordinat (support mouse dan touch)
        var clientX = e.clientX;
        var clientY = e.clientY;
        if (e.touches) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }
        
        var rect = chatWindow.getBoundingClientRect();
        var computedStyle = window.getComputedStyle(chatWindow);
        
        chatWindowDrag.startX = clientX;
        chatWindowDrag.startY = clientY;
        chatWindowDrag.initialLeft = parseFloat(computedStyle.left);
        chatWindowDrag.initialTop = parseFloat(computedStyle.top);
        chatWindowDrag.isDragging = true;
        
        chatWindow.style.cursor = 'grabbing';
        chatWindow.style.transition = 'none';
        chatWindow.style.opacity = '0.95';
        chatWindow.classList.add('dragging-window');
        
        document.addEventListener('mousemove', onChatWindowMove);
        document.addEventListener('mouseup', onChatWindowEnd);
        document.addEventListener('touchmove', onChatWindowMove, { passive: false });
        document.addEventListener('touchend', onChatWindowEnd);
    }
    
    function onChatWindowMove(e) {
        if (!chatWindowDrag.isDragging) return;
        e.preventDefault();
        
        // Dapatkan koordinat (support mouse dan touch)
        var clientX = e.clientX;
        var clientY = e.clientY;
        if (e.touches) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }
        
        var dx = clientX - chatWindowDrag.startX;
        var dy = clientY - chatWindowDrag.startY;
        
        var newLeft = chatWindowDrag.initialLeft + dx;
        var newTop = chatWindowDrag.initialTop + dy;
        
        // Batasi agar tidak keluar viewport
        var windowRect = chatWindow.getBoundingClientRect();
        var maxX = window.innerWidth - windowRect.width - 10;
        var maxY = window.innerHeight - windowRect.height - 10;
        var minX = 10;
        var minY = 60; // Biar tidak terlalu ke atas
        
        newLeft = Math.min(maxX, Math.max(minX, newLeft));
        newTop = Math.min(maxY, Math.max(minY, newTop));
        
        chatWindow.style.left = newLeft + 'px';
        chatWindow.style.top = newTop + 'px';
        chatWindow.style.right = 'auto';
        chatWindow.style.bottom = 'auto';
    }
    
    function onChatWindowEnd() {
        if (!chatWindowDrag.isDragging) return;
        
        chatWindowDrag.isDragging = false;
        chatWindow.style.cursor = '';
        chatWindow.style.transition = '';
        chatWindow.style.opacity = '1';
        chatWindow.classList.remove('dragging-window');
        
        // Simpan posisi window ke localStorage
        var left = chatWindow.style.left;
        var top = chatWindow.style.top;
        if (left && top && left !== 'auto' && top !== 'auto') {
            localStorage.setItem('chatWindowLeft', left);
            localStorage.setItem('chatWindowTop', top);
        }
        
        document.removeEventListener('mousemove', onChatWindowMove);
        document.removeEventListener('mouseup', onChatWindowEnd);
        document.removeEventListener('touchmove', onChatWindowMove);
        document.removeEventListener('touchend', onChatWindowEnd);
    }
    
    chatHeader.addEventListener('mousedown', startDragWindow);
    chatHeader.addEventListener('touchstart', startDragWindow, { passive: false });
}

// Fungsi untuk memuat posisi dari localStorage
function loadSavedPositions() {
    var chatButton = document.getElementById('chatButton');
    var chatWindow = document.getElementById('chatWindow');
    
    if (chatButton) {
        var savedLeft = localStorage.getItem('chatButtonLeft');
        var savedTop = localStorage.getItem('chatButtonTop');
        if (savedLeft && savedTop && savedLeft !== 'null' && savedTop !== 'null') {
            chatButton.style.left = savedLeft;
            chatButton.style.top = savedTop;
            chatButton.style.right = 'auto';
            chatButton.style.bottom = 'auto';
        }
    }
    
    if (chatWindow) {
        var savedWinLeft = localStorage.getItem('chatWindowLeft');
        var savedWinTop = localStorage.getItem('chatWindowTop');
        if (savedWinLeft && savedWinTop && savedWinLeft !== 'null' && savedWinTop !== 'null') {
            chatWindow.style.left = savedWinLeft;
            chatWindow.style.top = savedWinTop;
            chatWindow.style.right = 'auto';
            chatWindow.style.bottom = 'auto';
        }
    }
}

// Fungsi untuk menyembunyikan chat box (saat logout)
function hideChatBox() {
    var chatButton = document.getElementById('chatButton');
    var chatWindow = document.getElementById('chatWindow');
    
    if (chatButton) {
        chatButton.classList.add('hide-chat');
    }
    if (chatWindow) {
        chatWindow.classList.add('hide-chat');
        chatWindow.classList.remove('open');
    }
}

// Fungsi untuk menampilkan chat box (saat login)
function showChatBox() {
    var chatButton = document.getElementById('chatButton');
    var chatWindow = document.getElementById('chatWindow');
    
    if (chatButton) {
        chatButton.classList.remove('hide-chat');
    }
    if (chatWindow) {
        chatWindow.classList.remove('hide-chat');
    }
}

// Fungsi toggle chat window
function toggleChat() {
    // Jangan toggle jika sedang drag
    if (chatButtonDrag.isDragging || chatWindowDrag.isDragging) return;
    
    var chatWindow = document.getElementById('chatWindow');
    var chevron = document.querySelector('.chat-button .chevron');
    var chatBadge = document.getElementById('chatBadge');
    
    if (chatWindow) {
        if (chatWindow.classList.contains('open')) {
            chatWindow.classList.remove('open');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
            chatWindow.classList.add('open');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            if (chatBadge) chatBadge.style.display = 'none';
            setTimeout(function() {
                var input = document.getElementById('chatInput');
                if (input) input.focus();
            }, 300);
        }
    }
}

// Fungsi close chat
function closeChat() {
    var chatWindow = document.getElementById('chatWindow');
    var chevron = document.querySelector('.chat-button .chevron');
    
    if (chatWindow) {
        chatWindow.classList.remove('open');
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
}

// Fungsi AI Response
function getAIResponse(userMessage) {
    var msg = userMessage.toLowerCase().trim();
    
    if (msg.match(/^(hai|halo|hello|hey|selamat|assalamualaikum)/i)) {
        return "Halo! Selamat datang di Sistem Kunjungan BPRS Amanah Bangsa! 👋\n\nAda yang bisa saya bantu hari ini? Silakan tanyakan tentang:\n• Cara menggunakan sistem\n• Proses approve/reject\n• Export laporan\n• Manajemen user\n• Fitur-fitur lainnya\n\n💡 Tips: Anda bisa memindahkan (drag) chat window ini dari bagian header! (Support mobile)";
    }
    
    if (msg.match(/cara isi|mengisi data|input data|tambah data|form/i)) {
        return "📝 Cara Mengisi Data Kunjungan:\n\n1. Pilih Cabang\n2. Isi Nama AO\n3. Isi Nama Nasabah\n4. Isi No Pembiayaan (hanya angka)\n5. Isi Alamat lengkap\n6. Pilih Tanggal Kunjungan\n7. Isi Keterangan (opsional)\n8. Pilih Hasil Kunjungan\n9. Upload Foto Bukti (JPEG/PNG, max 5MB)\n10. Klik Simpan Data\n\n📸 Foto harus menunjukkan bukti kunjungan dengan timestamp dan lokasi.";
    }
    
    if (msg.match(/approve|menyetujui|cara approve|reject|menolak/i)) {
        return "✅ Proses Approval:\n\nManager klik tombol 'Approve' pada baris data yang ingin disetujui, bisa menambahkan catatan opsional.\n\n❌ Proses Reject:\nManager klik tombol 'Reject', kemudian isi alasan penolakan (wajib).\n\nCatatan: Hanya Manager yang bisa melakukan approve/reject.";
    }
    
    if (msg.match(/export|excel|pdf|word|download laporan/i)) {
        return "📊 Cara Export Laporan:\n\n• Export All - Klik tombol 'Export All' → pilih format (Excel/PDF/Word)\n• Export Selected - Centang checkbox data yang ingin diexport, lalu klik 'Export Selected'\n\n📎 Hasil export PDF menyertakan lampiran foto bukti kunjungan.";
    }
    
    if (msg.match(/status|pending|approved|rejected|disetujui|ditolak/i)) {
        return "📌 Status Data Kunjungan:\n\n• Pending (Kuning) - Data menunggu persetujuan Manager\n• Approved (Hijau) - Data telah disetujui Manager\n• Rejected (Merah) - Data ditolak Manager, AO bisa edit ulang";
    }
    
    if (msg.match(/role|hak akses|ao|manager|admin/i)) {
        return "👥 Role dan Hak Akses:\n\n• AO - Mengisi/mengedit data kunjungan sendiri\n• Manager - Menyetujui/menolak data, melihat data cabangnya\n• Admin - Mengelola user, menghapus data, akses penuh";
    }
    
    if (msg.match(/bantuan|help|menu|hal yang bisa ditanyakan/i)) {
        return "📋 Yang Bisa Saya Bantu:\n\n1. Cara mengisi data kunjungan\n2. Proses approve/reject data\n3. Export laporan (Excel/PDF/Word)\n4. Status data (Pending/Approved/Rejected)\n5. Role dan hak akses\n6. Notifikasi dan reminder\n7. Manajemen user (Admin)\n8. Memindahkan (drag) chat window (support mobile!)\n\nSilakan tanyakan lebih spesifik!";
    }
    
    if (msg.match(/drag|pindah|memindahkan|move/i)) {
        return "🖱️ Cara Memindahkan Chat:\n\n• Chat Button: Sentuh tahan (tap and hold) lalu geser tombol AI Assistant ke mana saja\n• Chat Window: Sentuh tahan pada bagian header (warna ungu) lalu geser\n\nPosisi akan otomatis disimpan untuk sesi berikutnya!\n\n📱 Support penuh untuk mobile (Android & iOS)!";
    }
    
    if (msg.match(/terima kasih|thank|thanks|makasih/i)) {
        return "Sama-sama! 😊 Senang bisa membantu. Jika ada pertanyaan lain, jangan ragu untuk bertanya ya!";
    }
    
    return "Maaf, saya belum mengerti pertanyaan Anda 🤔\n\nCoba tanyakan hal berikut:\n• 'Cara mengisi data kunjungan?'\n• 'Bagaimana cara approve data?'\n• 'Apa perbedaan status pending dan approved?'\n• 'Cara export ke Excel?'\n• 'Bagaimana cara memindahkan chat?'\n\nAtau ketik 'bantuan' untuk melihat semua topik yang bisa saya bantu.";
}

// Fungsi send message
function sendMessage() {
    var input = document.getElementById('chatInput');
    var message = input.value.trim();
    if (!message) return;
    
    addMessage(message, 'user');
    input.value = '';
    
    var sendBtn = document.getElementById('chatSendBtn');
    sendBtn.disabled = true;
    input.disabled = true;
    
    showTypingIndicator();
    
    setTimeout(function() {
        var response = getAIResponse(message);
        hideTypingIndicator();
        addMessage(response, 'bot');
        sendBtn.disabled = false;
        input.disabled = false;
        input.focus();
    }, 500);
}

function handleChatKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function sendSuggestion(text) {
    document.getElementById('chatInput').value = text;
    sendMessage();
}

function addMessage(text, sender) {
    var messagesContainer = document.getElementById('chatMessages');
    var now = new Date();
    var timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    
    var messageDiv = document.createElement('div');
    messageDiv.className = 'message ' + sender;
    
    var avatar = sender === 'bot' 
        ? '<div class="message-avatar"><i class="fas fa-robot"></i></div>'
        : '<div class="message-avatar"><i class="fas fa-user"></i></div>';
    
    var formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    formattedText = formattedText.replace(/\n/g, '<br>');
    
    messageDiv.innerHTML = avatar + '<div class="message-content">' + formattedText + '<span class="message-time">' + timeString + '</span></div>';
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function showTypingIndicator() {
    var messagesContainer = document.getElementById('chatMessages');
    var typingDiv = document.createElement('div');
    typingDiv.className = 'message bot';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = '<div class="message-avatar"><i class="fas fa-robot"></i></div><div class="chat-typing"><span></span><span></span><span></span></div>';
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function hideTypingIndicator() {
    var typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) typingIndicator.remove();
}

// Inisialisasi event listener
document.addEventListener('DOMContentLoaded', function() {
    // Mulai dengan chat box tersembunyi (belum login)
    hideChatBox();
    
    // Inisialisasi drag & drop (support mobile)
    initDragAndDrop();
    
    // Load posisi tersimpan
    loadSavedPositions();
    
    // Event listener untuk tombol chat
    var chatButton = document.getElementById('chatButton');
    var closeChatBtn = document.getElementById('closeChatBtn');
    
    if (chatButton) {
        chatButton.addEventListener('click', function(e) {
            // Jangan toggle jika sedang drag
            if (chatButtonDrag.isDragging) return;
            e.preventDefault();
            e.stopPropagation();
            toggleChat();
        });
        
        // Untuk mobile, cegah double fire
        chatButton.addEventListener('touchstart', function(e) {
            if (chatButtonDrag.isDragging) {
                e.preventDefault();
            }
        });
    }
    
    if (closeChatBtn) {
        closeChatBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeChat();
        });
    }
    
    // Cek login setiap 500ms
    var loginCheckInterval = setInterval(function() {
        var mainContent = document.getElementById('mainContent');
        var isLoggedIn = mainContent && mainContent.style.display === 'block';
        
        if (isLoggedIn && typeof currentUser !== 'undefined' && currentUser) {
            showChatBox();
            
            if (!window.greetingShown) {
                window.greetingShown = true;
                setTimeout(function() {
                    var greeting = 'Selamat datang kembali, ' + currentUser.name + '! 👋\n\n';
                    if (currentUser.role === 'ao') {
                        greeting += 'Sebagai AO ' + currentUser.cabang + ', Anda bisa mengisi dan mengedit data kunjungan.';
                    } else if (currentUser.role === 'manager') {
                        greeting += 'Sebagai Manager ' + currentUser.cabang + ', Anda bisa menyetujui/menolak data kunjungan.';
                    } else if (currentUser.role === 'admin') {
                        greeting += 'Sebagai Administrator, Anda memiliki akses penuh ke sistem.';
                    }
                    greeting += '\n\n💡 Tips: Anda bisa drag chat window ini dari header untuk memindahkannya! (Support mobile)';
                    addMessage(greeting, 'bot');
                }, 1500);
            }
        } else {
            hideChatBox();
            window.greetingShown = false;
        }
    }, 500);
});
</script>
</body>
</html>
