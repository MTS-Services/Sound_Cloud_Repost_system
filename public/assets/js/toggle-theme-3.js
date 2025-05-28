document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        current: localStorage.getItem('theme') || 'system',

        init() {
            this.updateTheme();

            // Watch for system preference changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (this.current === 'system') {
                    this.updateTheme();
                }
            });
        },

        updateTheme() {
            localStorage.setItem('theme', this.current);

            if (this.current === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', systemPrefersDark);
                document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.classList.toggle('dark', this.current === 'dark');
                document.documentElement.setAttribute('data-theme', this.current);
            }
        },

        toggleTheme() {
            const options = ['system', 'light', 'dark'];
            const nextIndex = (options.indexOf(this.current) + 1) % options.length;
            this.current = options[nextIndex];
            this.updateTheme();
        },

        get darkMode() {
            if (this.current === 'system') {
                return window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            return this.current === 'dark';
        }
    });
});
