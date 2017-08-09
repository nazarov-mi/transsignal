import Vue from 'vue'
import Resource from 'vue-resource'
import Api from './http/Api'
import App from './App'
import router from './router'

window.Vue = Vue;
window.Api = new Api();

Vue.config.productionTip = false
Vue.use(Resource)

/* eslint-disable no-new */
new Vue({
	el: '#app',
	router,
	render: h => h(App)
})
