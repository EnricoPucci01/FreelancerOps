var staticCacheName = "static-Site-1";
var projectLength = 0;
var options;
const channel = new BroadcastChannel('sw-messages');

var filesToCache = [
    '/',
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/postProjectJS.js',
    '/images/maskable_icon7272.png',
    '/images/maskable_icon9696.png',
    '/images/maskable_icon128128.png',
    '/images/maskable_icon144144.png',
    '/images/maskable_icon152152.png',
    '/images/LogoTA.png',
    '/cssStyle.css',
    '/chatboxCSS.css',
    'images/landingClient.png',
    'images/landingFreelancer.jpg',
    'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css',
    'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css',
    'https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js'
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                console.log("caching static site");
                return cache.addAll(filesToCache);
            })
    );
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(cacheName => (cacheName.startsWith("static-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request.url, {ignoreSearch:true})
            .then(response => {
                console.log("URL: ",event.request.url);
                console.log(response);
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
    if (event.tag == "offlineSync") {
        event.waitUntil(BackOnline());
    }
});

function submitpostproject() {
    console.log("Data Form: ", options);

    fetch("/submitpostproject", options).then((response) => {
        console.log("no JSON: ", response);
        return response.json()
    })
        .then((responseJSON) => {
            console.log(responseJSON);
        });
}

function BackOnline() {
    channel.postMessage({ title: 'online' });
}

self.addEventListener('message', (event) => {
    let notification = event.data;
    if (notification.action == "notification") {
        fetch("/backSyncProject").then((response) => response.json()).then((responseJSON) => {
            // /console.log(JSON.stringify(responseJSON));
            var obj = JSON.parse(JSON.stringify(responseJSON));
            var length = obj.length;
            if (length > projectLength) {
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
    } else if (notification.action == "postProject") {
        options = notification.header;
        console.log(options);
    }
});

function badge() {
    console.log('sync Triggered');
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
