const CACHE_NAME = 'sorteador-v1.0.1';
const ASSETS = [
    './',
    './index.php',
    './style.css',
    './app.js',
    './manifest.json',
    './icon-192.png',
    './icon-512.png',
    './favicon.png',
    './apple-touch-icon.png'
];

// Install Service Worker and cache assets immediately
self.addEventListener('install', (e) => {
    self.skipWaiting();
    e.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS);
        }).catch(err => console.warn('SW Cache error:', err))
    );
});

// Activate Service Worker and clean old caches immediately
self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Pure Network-First strategy per 4U.IA standard with robust offline fallback
self.addEventListener('fetch', (e) => {
    if (e.request.method !== 'GET') return;

    if (!e.request.url.startsWith(self.location.origin)) {
        return;
    }

    e.respondWith(
        fetch(e.request)
            .then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(e.request, responseClone);
                    });
                }
                return networkResponse;
            })
            .catch(async () => {
                const cached = await caches.match(e.request, { ignoreSearch: true });
                if (cached) return cached;

                if (e.request.mode === 'navigate') {
                    const rootCached = (await caches.match('./')) || (await caches.match('./index.php'));
                    if (rootCached) return rootCached;
                }

                return new Response('Offline - Conteúdo não disponível sem conexão.', {
                    status: 503,
                    statusText: 'Service Unavailable',
                    headers: { 'Content-Type': 'text/plain; charset=utf-8' }
                });
            })
    );
});
