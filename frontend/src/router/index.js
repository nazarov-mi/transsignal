import Vue from 'vue'
import Router from 'vue-router'
import main from './main'
import admin from './admin'

Vue.use(Router)

export default (() => {
	var router = new Router({
		routes: [
			main,
			admin
		],
		linkActiveClass: 'is-active',
		// mode: 'history'
	});

	/*router.beforeEach((to, from, next) => {
		if (to.matched.some(record => record.meta.requiresAuth)) {
			if (USER DOES NOT EXIST IN LOCAL STORAGE) {
				next({
					name: 'login',
					query: {
						redirect: to.fullPath,
					},
				});
			} else {
				next();
			}
		} else {
			next();
		}
		to.matched.some(record => {
			console.log(record.path, record.meta.requiresAuth);
		});
		next();
	});*/

	return router;
})();
