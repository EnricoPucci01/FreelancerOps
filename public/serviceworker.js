// const { define } = require("laravel-mix");

var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/images/maskable_icon7272.png',
    '/images/maskable_icon9696.png',
    '/images/maskable_icon128128.png',
    '/images/maskable_icon144144.png',
    '/images/maskable_icon152152.png',
    '/images/LogoTA.png',
  	'/cssStyle.css',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});

self.addEventListener('sync', event =>{

    if (event.tag == 'sync') {
        console.log('sync');
        event.waitUntil(badge());
    }
});

function badge(){
    fetch("/setAppBadge").then(function(response){
        if(response.status==200){
            console.log('onload');
        }
    });
}
