<div x-data="{ lastActivity: Date.now() }" x-init="(() => {
    ['mousemove', 'scroll', 'click', 'keydown'].forEach(event => {
        window.addEventListener(event, () => { lastActivity = Date.now() });
    });
    setInterval(() => {
        if (Date.now() - lastActivity < 120000) {
            $wire.ping();
        }
    }, 30000);
})()">
</div>
