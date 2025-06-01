document.addEventListener('alpine:init', () => {
    Alpine.store('password', {
        showPassword: false,
        togglePassword() {
            this.showPassword = !this.showPassword;
        }
    });
});