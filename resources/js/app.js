/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
// service-worker.js
var CACHE_NAME = 'my-site-cache-v1';
var urlsToCache = [
    '/styles/sweetalert2.css',
    '/styles/socicon.css',
    '/styles/line-awesome.css',
    '/styles/flaticon.css',
    '/styles/all.min.css',
    '/styles/style.bundle.css',
    '/styles/light.css',
    '/styles/dark.css',
    '/styles/custom.style.css',
    '/styles/pace.css',
    '/styles/bootstrap-select.css',
    '/styles/datatables.bundle.css',
    '/styles/toastr.css',
    '/styles/perfect-scrollbar.css',
    '/styles/morris.css',
];


// Check if service workers are supported
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
        // Registration was successful
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      }, function(err) {
        // registration failed :(
        console.log('ServiceWorker registration failed: ', err);
      });
    });
}