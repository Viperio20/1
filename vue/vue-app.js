"use strict";

axios.defaults.baseURL = '/api.php';

Vue.filter('datetime', function (value) {

	if ( !moment(value).isValid() ) return `-`;	

	let 
		formatter = new Intl.DateTimeFormat("default", {
			year: 'numeric',
			month: 'numeric',
			day: 'numeric',			
			hour: 'numeric',
			minute: 'numeric',			
		});

	return formatter.format( moment(value).toDate() );
});


Vue.component('page-login', LoginPage );

var vueApp = new Vue({
	el: '#vue-app-container',
	router,
	store,
	
	
	
	mounted() {
		
		
	},
	
	computed: {
		auth() { return this.$store.getters["account/auth"] },
		login() { return this.$store.getters["account/login"] },
		name() { return this.$store.getters["account/name"] },
		role() { return this.$store.getters["account/role"] },
		page_title() { return  this.$store.getters.page_title },
	},

	created() {
		this.$store.dispatch("init");
		//this.$store.commit("lang/setLang", this.$route.meta.lang);
	},

	
});


