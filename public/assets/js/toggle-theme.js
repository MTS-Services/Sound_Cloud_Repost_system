// document.addEventListener('alpine:init', () => {
//     Alpine.store('theme', {
//         current: localStorage.getItem('theme') || 'light',  // Default to 'light'

//         init() {
//             this.updateTheme();
//         },

//         updateTheme() {
//             localStorage.setItem('theme', this.current);

//             const isDark = this.current === 'dark';
//             document.documentElement.classList.toggle('dark', isDark);
//             document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');

//             if (window.lucide) lucide.createIcons();  // Optional, to refresh icons
//         },

//         toggleTheme() {
//             this.current = this.current === 'light' ? 'dark' : 'light';
//             this.updateTheme();
//         },

//         get darkMode() {
//             return this.current === 'dark';
//         }
//     });
// });
document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        current: localStorage.getItem('theme') || 'light',

        init() {
            this.updateTheme();
            
            // Listen for Livewire navigation events
            document.addEventListener('livewire:navigated', () => {
                // Re-apply theme after navigation
                this.updateTheme();
            });
        },

        updateTheme() {
            localStorage.setItem('theme', this.current);

            const isDark = this.current === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');

            // Refresh icons after theme change
            if (window.lucide && lucide.createIcons) {
                lucide.createIcons();
            }
        },

        toggleTheme() {
            this.current = this.current === 'light' ? 'dark' : 'light';
            this.updateTheme();
        },

        get darkMode() {
            return this.current === 'dark';
        }
    });
});

// Also ensure theme is applied immediately on page load
document.addEventListener('DOMContentLoaded', function() {
    const theme = localStorage.getItem('theme') || 'light';
    const isDark = theme === 'dark';
    
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
});

// Handle Livewire navigation specifically
document.addEventListener('livewire:navigated', function() {
    // Reapply theme after Livewire navigation
    const theme = localStorage.getItem('theme') || 'light';
    const isDark = theme === 'dark';
    
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    
    // Refresh icons
    if (window.lucide && lucide.createIcons) {
        lucide.createIcons();
    }
});