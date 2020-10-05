/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

let authorizations = require('./authorizations')

window.Vue.prototype.authorize = function (...params) {
    if (! window.ApplicationCache.singedIn) return false

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1])
    }

    return params[0](window.ApplicationCache.user)
}

Vue.prototype.signedIn = window.App.signedIn

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('thread-view', require('./pages/Thread.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);

window.events = new Vue()

window.flash = function (message, level = 'success') {
    window.events.$emit('flash', { message, level })
}

const app = new Vue({
    el: '#app',
});
