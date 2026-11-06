<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* SweetAlert2 Modal Styling */
    .swal2-popup {
        border-radius: 1.25rem !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .swal2-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
    }
    .swal2-html-container {
        font-size: 0.8125rem !important;
        color: #475569 !important;
    }

    /* Colorful SweetAlert2 Toasts */
    .swal2-colored-toast {
        border-radius: 1rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.15) !important;
        padding: 0.85rem 1.25rem !important;
        font-weight: 600 !important;
    }
    .swal2-colored-toast .swal2-title {
        color: #ffffff !important;
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        line-height: 1.35 !important;
    }
    .swal2-colored-toast .swal2-timer-progress-bar {
        background: rgba(255, 255, 255, 0.75) !important;
        height: 3px !important;
    }
    .swal2-colored-toast .swal2-icon {
        margin: 0 0.75rem 0 0 !important;
    }
    .swal2-colored-toast .swal2-icon.swal2-icon-show {
        border-color: rgba(255, 255, 255, 0.85) !important;
    }

    /* Status Toggle Switch CSS */
    .switch-track {
        position: relative;
        display: inline-block;
        width: 38px !important;
        height: 22px !important;
        border-radius: 9999px;
        transition: background-color 0.2s ease-in-out;
        cursor: pointer;
        flex-shrink: 0;
        vertical-align: middle;
    }
    .switch-track.active {
        background-color: #10b981 !important; /* emerald-500 */
    }
    .switch-track.inactive {
        background-color: #cbd5e1 !important; /* slate-300 */
    }
    .switch-thumb {
        position: absolute;
        top: 3px !important;
        left: 3px !important;
        width: 16px !important;
        height: 16px !important;
        border-radius: 9999px;
        background-color: #ffffff !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.15), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease-in-out;
        pointer-events: none;
    }
    .switch-track.active .switch-thumb {
        transform: translateX(16px) !important;
    }
    .switch-track.inactive .switch-thumb {
        transform: translateX(0) !important;
    }

    /* Custom thin scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
