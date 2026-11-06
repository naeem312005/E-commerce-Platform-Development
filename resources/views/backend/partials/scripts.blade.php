<script>
    // SweetAlert2 Colored Toast Notifications
    const Toast = {
        fire: function(options) {
            const icon = options.icon || 'info';
            const title = options.title || '';
            const opts = options || {};

            let bg = '#3b82f6'; // default info blue
            if (icon === 'success') bg = '#10b981'; // emerald green
            if (icon === 'error') bg = '#ef4444'; // red
            if (icon === 'warning') bg = '#f59e0b'; // amber

            return Swal.fire({
                toast: true,
                position: opts.position || 'top-end',
                icon: icon,
                iconColor: '#ffffff',
                title: title,
                text: opts.text || '',
                showConfirmButton: false,
                timer: opts.timer || 3500,
                timerProgressBar: true,
                color: '#ffffff',
                background: bg,
                customClass: {
                    popup: 'swal2-colored-toast'
                },
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
        },
        success: function(title, text = '') { return this.fire({ icon: 'success', title, text }); },
        error: function(title, text = '') { return this.fire({ icon: 'error', title, text }); },
        warning: function(title, text = '') { return this.fire({ icon: 'warning', title, text }); },
        info: function(title, text = '') { return this.fire({ icon: 'info', title, text }); }
    };
    window.Toast = Toast;

    // Async AJAX Status Toggle Switch Handler
    async function toggleStatus(url, buttonElement, activeText = 'Active', inactiveText = 'Inactive') {
        buttonElement.disabled = true;
        const track = buttonElement.querySelector('.switch-track') || buttonElement.querySelector('.toggle-track');
        const label = buttonElement.querySelector('.switch-label') || buttonElement.querySelector('.toggle-label');

        try {
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            if (data.success) {
                if (data.is_active) {
                    track.classList.remove('inactive', 'bg-slate-300');
                    track.classList.add('active', 'bg-emerald-500');
                    if (label) {
                        label.innerText = activeText;
                        label.classList.remove('text-slate-400');
                        label.classList.add('text-emerald-700');
                    }
                } else {
                    track.classList.remove('active', 'bg-emerald-500');
                    track.classList.add('inactive', 'bg-slate-300');
                    if (label) {
                        label.innerText = inactiveText;
                        label.classList.remove('text-emerald-700');
                        label.classList.add('text-slate-400');
                    }
                }

                Toast.fire({
                    icon: 'success',
                    title: data.message || ('Status updated to ' + (data.is_active ? activeText : inactiveText))
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.message || 'Failed to update status.'
                });
            }
        } catch (error) {
            console.error('Toggle error:', error);
            Toast.fire({
                icon: 'error',
                title: 'An error occurred while updating status.'
            });
        } finally {
            buttonElement.disabled = false;
        }
    }

    // Mobile Sidebar Drawer Controller
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('mobile-sidebar-toggle');
        const closeBtn = document.getElementById('sidebar-close-btn');
        const sidebar = document.getElementById('sidebar');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        if (closeBtn && sidebar) {
            closeBtn.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
            });
        }
    });
</script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.fire({
                icon: 'success',
                title: "{{ addslashes(session('success')) }}"
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.fire({
                icon: 'error',
                title: "{{ addslashes(session('error')) }}"
            });
        });
    </script>
@endif

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Toast.fire({
                icon: 'warning',
                title: "{{ addslashes($errors->first()) }}"
            });
        });
    </script>
@endif
