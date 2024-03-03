// const { define } = require("laravel-mix");

var staticCacheName = "pwa-v" + new Date().getTime();
var projectLength = 0;
const broadcast = new BroadcastChannel('sw-update-channel');
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
                // console.log("URL: ", event.request.url);
                // console.log("RESP: ", response);
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});

self.addEventListener('sync', event => {
    if (event.tag == 'sync') {
        event.waitUntil(badge());
    }
});

self.addEventListener('message', (event) => {
    let notification = event.data;

    fetch("/backSyncProject").then((response) => response.json()).then((responseJSON) => {
        // /console.log(JSON.stringify(responseJSON));
        var obj = JSON.parse(JSON.stringify(responseJSON));
        var length = obj.length;
        if(length>projectLength){
            projectLength = length;
            self.registration.showNotification(
                notification.title,
                notification.options
            ).catch((error) => {
                console.log(error);
            });
        }
        console.log(projectLength);
    });

});

function badge() {
    fetch("/setAppBadge")
        .then((response) => response.json())
        .then((responseJSON) => {
            isSupported('v2', responseJSON);
            isSupported('v1', responseJSON);
            isSupported('v3', responseJSON);
        });

}



function setBadge(badgeVal) {
    if (navigator.setAppBadge) {
        navigator.setAppBadge(badgeVal);
    } else if (navigator.setExperimentalAppBadge) {
        navigator.setExperimentalAppBadge(badgeVal);
    } else if (window.ExperimentalBadge) {
        window.ExperimentalBadge.set(badgeVal);
    }
}

function clearBadge() {
    if (navigator.clearAppBadge) {
        navigator.clearAppBadge();
    } else if (navigator.clearExperimentalAppBadge) {
        navigator.clearExperimentalAppBadge();
    } else if (window.ExperimentalBadge) {
        window.ExperimentalBadge.clear();
    }
}

function isSupported(kind, badgeVal) {
    setBadge(badgeVal);
}
