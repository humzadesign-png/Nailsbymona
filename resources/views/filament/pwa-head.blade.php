<link rel="manifest" href="/manifest.json">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="NbM Admin">
<link rel="apple-touch-icon" href="/icon-192.png">
<meta name="theme-color" content="#BFA4CE">

@auth
<script>
(function () {
    const VAPID_PUBLIC = '{{ config("webpush.vapid.public_key") }}';
    const SUB_URL      = '{{ route("push.subscription.store") }}';
    const CSRF         = '{{ csrf_token() }}';

    async function subscribe() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

        const reg = await navigator.serviceWorker.register('/sw.js');
        await navigator.serviceWorker.ready;

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return;

        const existing = await reg.pushManager.getSubscription();
        const sub = existing || await reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC),
        });

        await fetch(SUB_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(sub.toJSON()),
        });
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw     = atob(base64);
        return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
    }

    // Run after page is interactive
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', subscribe);
    } else {
        subscribe();
    }
})();
</script>
@endauth
