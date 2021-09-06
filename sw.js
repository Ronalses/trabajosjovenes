var CACHE_NAME = 'TrabajosJovenes';
var urlsToCache = [
  'https://trabajosjovenes.cl/offline/'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
      .catch((error)=>console.log(error))
  );
});

this.addEventListener('fetch',(event)=>{
  event.respondWith(
    fetch(event.request)
    .catch(()=>{
      return caches.match(urlsToCache)
    })
  )
})


