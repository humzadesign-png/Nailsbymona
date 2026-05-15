self.addEventListener('push', function (event) {
    const data = event.data ? event.data.json() : {};

    const title   = data.title   || 'Nails by Mona';
    const options = {
        body:    data.body    || '',
        icon:    data.icon    || '/icon-192.png',
        badge:   '/icon-192.png',
        tag:     data.tag     || 'nbm-notification',
        data:    { url: data.url || '/admin' },
        vibrate: [200, 100, 200],
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = event.notification.data?.url || '/admin';
    event.waitUntil(clients.openWindow(url));
});
