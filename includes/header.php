<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.min.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Dashboard</title>

</head>

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

body {
    background: radial-gradient(at 0% 0%, rgba(224, 231, 255, 0.3) 0, transparent 50%),
                radial-gradient(at 50% 0%, rgba(243, 244, 246, 0.4) 0, transparent 50%),
                radial-gradient(at 100% 0%, rgba(238, 242, 255, 0.3) 0, transparent 50%),
                #f8fafc;
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.3);
    border-radius: 9999px;
}
::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.5);
 }
 /* Glass Card Effect */ 
.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}

.hover-lift {
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s;
}
.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(99, 102, 241, 0.05);
}

input[type="text"], input[type="number"], input[type="date"], select, textarea {
    transition: border-color 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
input[type="text"]:focus, input[type="number"]:focus, input[type="date"]:focus, select:focus, textarea:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    outline: none !important;
}
/* Sidebar Styling */
.sidebar {
    width: 260px;
    min-height: 100vh;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-right: 1px solid rgba(226, 232, 240, 0.8);
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 50;
}

.sidebar li a {
    transition: background-color 0.2s, color 0.2s, transform 0.2s;
}
.sidebar li a:hover {
    transform: translateX(4px);
}
/* Hamburger Button */
.hamburger-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 12px;
    color: #475569;
    transition: background-color 0.2s;
}
.hamburger-btn:hover { background: rgba(241, 245, 249, 0.8); }

/* Sidebar mobile backdrop overlay */
.sidebar-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.3);
    backdrop-filter: blur(4px);
    z-index: 40;
}
.sidebar-backdrop.active { display: block; }
/* Mobile Media Query */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        transform: translateX(-100%);
        z-index: 50;
    }
    .sidebar.open {
        transform: translateX(0);
    }
    .hamburger-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .top-navbar {
        padding: 12px 16px !important;
        margin-bottom: 20px !important;
    }
    .page-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
        margin-bottom: 20px !important;
    }
    div.dt-container .dt-layout-row {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    div.dt-container .dt-search input {
        width: 100% !important;
        min-width: 100%;
    }
    .tasks-desktop-table { display: none !important; }
    .tasks-mobile-cards  { display: block !important; }
}
/* Tablet Media Query */
@media (min-width: 769px) and (max-width: 1024px) {
    .sidebar {
        width: 220px;
    }
    div.dt-container .dt-layout-row {
        flex-wrap: wrap !important;
    }
    div.dt-container .dt-search input {
        min-width: 180px;
    }
}

.tasks-mobile-cards { display: none; }

/* Custom DataTable UI upgrades */
div.dt-container .dt-layout-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 15px;
    margin-bottom: 20px;
}
div.dt-container .dt-length {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #475569;
}
div.dt-container .dt-length select {
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    border-radius: 12px !important;
    padding: 8px 12px !important;
    outline: none !important;
    background-color: white !important;
    cursor: pointer;
}
div.dt-container .dt-search {
    display: flex;
    align-items: center;
    gap: 10px;
}
div.dt-container .dt-search input {
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    border-radius: 14px !important;
    padding: 10px 16px !important;
    outline: none !important;
    min-width: 250px;
    background: rgba(255, 255, 255, 0.8);
}
div.dt-container .dt-info {
    font-size: 14px;
    color: #64748b;
    padding-top: 10px;
}
div.dt-container .dt-paging {
    margin-top: 10px;
}
div.dt-container .dt-paging .dt-paging-button {
    border-radius: 12px !important;
    padding: 8px 14px !important;
    margin: 0 4px !important;
    border: 1px solid rgba(226, 232, 240, 0.6) !important;
    background: white !important;
    color: #475569 !important;
    transition: all 0.2s;
}
div.dt-container .dt-paging .dt-paging-button:hover {
    background: #f1f5f9 !important;
    border-color: rgba(226, 232, 240, 1) !important;
}
div.dt-container .dt-paging .current {
    background: #4f46e5 !important;
    color: white !important;
    border-color: #4f46e5 !important;
}
/* Task Row Highlight */
table.dataTable tbody tr.highlight-today > *,
table.dataTable tbody tr.highlight-today {
    background-color: #fef2f2 !important;
    color: #ef4444 !important;
    box-shadow: none !important;
}
table.dataTable tbody tr.highlight-overdue > *,
table.dataTable tbody tr.highlight-overdue {
    background-color: #fff5f5 !important;
    color: #e11d48 !important;
    box-shadow: none !important;
}
table.dataTable tbody tr.highlight-upcoming > *,
table.dataTable tbody tr.highlight-upcoming {
    background-color: #fffbeb !important;
    color: #b45309 !important;
    box-shadow: none !important;
}
table.dataTable tbody tr.completed-task > *,
table.dataTable tbody tr.completed-task {
    background-color: #ffffff !important;
    color: #111214 !important;
}
/* Mobile Task Cards */
.task-card {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: transform 0.2s, box-shadow 0.2s;
}
.task-card.card-overdue  { background: #fff5f5; border-color: #fecaca; }
.task-card.card-today    { background: #fef2f2; border-color: #fca5a5; }
.task-card.card-upcoming { background: #fffbeb; border-color: #fef08a; }
.task-card.card-completed{ background: #ffffff; opacity: 0.8; }

/* Text Alignment Fix */
.task-card-title {
    font-weight: 700;
    font-size: 16px;
    color: #1e293b;
    margin-bottom: 6px;
}
.task-card-desc {
    font-size: 14px;
    color: #475569;
    margin-bottom: 12px;
    line-height: 1.6;
}
.task-card-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
}
.task-card-actions {
    display: flex;
    gap: 10px;
}
.task-card-actions a {
    flex: 1;
    text-align: center;
    padding: 10px 0;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .2s;
}
.task-card-actions a:hover { opacity: 0.9; }

table, th, td, table input, table select, table textarea, .dt-type-numeric {
    text-align: left !important;
}
.dt-empty, .dataTables_empty {
    text-align: center !important;
}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.8/js/dataTables.min.js"></script>