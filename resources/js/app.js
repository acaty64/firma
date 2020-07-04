require('./bootstrap');

window.Vue = require('vue');

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('sign-component', require('./components/SignComponent.vue').default);

const app = new Vue({
    el: '#app',
});
