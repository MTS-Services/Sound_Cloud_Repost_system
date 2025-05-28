document.addEventListener('alpine:init', () => {
  Alpine.store('theme', {
    init() {
      this.current = localStorage.getItem('theme') || 'system';
      this.updateTheme();

      // Watch for system preference changes
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (this.current === 'system') {
          this.updateTheme();
        }
      });
    },
    current: 'system',
    updateTheme() {
      // Save to localStorage
      localStorage.setItem('theme', this.current);

      // Apply dark class based on theme
      if (this.current === 'system') {
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', systemPrefersDark);
        document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' :
          'light');
      } else {
        document.documentElement.classList.toggle('dark', this.current === 'dark');
        document.documentElement.setAttribute('data-theme', this.current);
      }
    }
  });
});