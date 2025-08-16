

// Alpine.js store - simplified and optimized
document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        current: localStorage.getItem('theme') || 'light',

        init() {
            // Don't reapply theme here since it's already applied
            // Just ensure icons are refreshed
            if (window.lucide && lucide.createIcons) {
                lucide.createIcons();
            }
        },

        toggleTheme() {
            this.current = this.current === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.current);
            
            const isDark = this.current === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
            
            // Refresh icons after theme change
            if (window.lucide && lucide.createIcons) {
                lucide.createIcons();
            }
        },

        get darkMode() {
            return this.current === 'dark';
        }
    });
});