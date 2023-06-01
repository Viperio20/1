'use strict';

const routes = 
[

/*
    {
        "path": "/modifications",
        "meta": {
            "title": "Modifications"
        },
        "component": ModificationsPage
    },
*/

	{ path: '/', component: HomePage, meta: { title: "Сервіс документів" } },
	
	
    {
        "path": "/templates-add",
        "component": TemplatesPostPage,
        "props": {
            "method": "post"
        },
        "meta": {
            "title": "Додати шаблон"
        }
    },
	
	
    {
        "path": "/templates-edit/:item_id",
        "component": TemplatesPatchPage,
        "props": {
            "method": "patch"
        },
        "meta": {
            "title": "Змінити шаблон"
        }
    },	

    {
        "path": "/templates-detail/:item_id",
        "component": TemplatesDetailPage,
        "props": {
            "method": "detail"
        },
        "meta": {
            "title": "Створити документ"
        }
    },	




	
    {
        "path": "/*",
        "meta": {
            "title": "Сервіс документів"
        },
        "component": HomePage
    }
];



/*

	{ path: '/', component: NestedPage, 
		children: [
			{ path: '', component: HomePage, meta: { title: "Главная - CRM Rincom" }, },
			{ path: 'orders', component: NestedPage, 
				children: [
					{ path: '', component: OrdersPage, meta: { title: "Заказы поставщикам - CRM Rincom" } },
					//{ path: ':item_id', component: PowierzenieViewPage, meta: { title: "Inwentaryzacja - Flota" } },	
					{ path: 'edit/:item_id', component: OrdersEditPage, meta: { title: "Редактировать заказ - CRM Rincom" }, props: { method: "patch" } },
					{ path: 'add', component: OrdersEditPage, meta: { title: "Добавить заказ - CRM Rincom" }, props: { method: "post" } },
				]
			},	
		]
	},	
	
	
	
	
	{ path: '/login', component: LoginPage, meta: { title: "Авторизация на сайте - CRM Rincom" } },
	
	{ path: '/orders', component: OrdersPage, meta: { title: "Заказы поставщикам - CRM Rincom" } },
	
	{ path: '/forbidden', component: Page403, meta: { title: "Access Denied!" } },
	{ path: '/*', component: Page404, meta: { title: "Page not found!" } },

*/

const router = new VueRouter({
    base: '/',
    mode: 'history',	
	routes: routes,
	linkActiveClass: "",
	linkExactActiveClass: "active",	
});

	
router.beforeEach((to, from, next) => {
	
	if ( to.meta ) {
		store.commit("setPageTitle", to.meta.title);
		
		if ( to.meta.role ) store.commit("setPageRole", to.meta.role);
	};
	store.prev_route_path = from.path;
	next()
})	