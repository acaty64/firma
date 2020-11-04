require('./bootstrap');

window.Vue = require('vue');

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('sign-component', require('./components/SignComponent.vue').default);
Vue.component('ce-component', require('./components/CEComponent.vue').default);
Vue.component('c1m-component', require('./components/C1MComponent.vue').default);

const app = new Vue({
    el: '#app',
});
