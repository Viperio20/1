"use strict";


const store = new Vuex.Store({
	
	modules: {
		account: store_account,
		alert: store_alert,
	},	
	
	state: {
		server_time: 0,
		lang: "en",
		
		page_title: "Home",
		page_role: undefined,
		prev_route_path: undefined,
		
		
		templates: [],

		
	}, 
	getters: {

		lang: state => { return state.lang },
		page_title: state => { return state.page_title },
		
		
		templates: state => {  return state.templates },
		
	},
	
	mutations: {
		
		setLang( state, data ) { state.lang = data  },	
		set_curr_time( state, data ) { state.curr_time = data  },	
		
		

		setPageTitle( state, data ) { 
			document.title = state.page_title = data;
		},	
		
		setPageRole( state, data ) { state.page_role = data },
		
		setData( state, data ) {
			if ( data.templates ) state.templates = data.templates;
 		},		

		patchTemplates( state, data ) { FW.mutation.patchById( state, data, "templates" ) },			
		postTemplates( state, data ) { FW.mutation.post( state, data, "templates" ) },
		deleteTemplates( state, data ) { FW.mutation.deleteById( state, data, "templates" ) },

		
	},
	
	actions: {
		
		init( context ) {	
		
			
		
			async function initialization() {
				await context.dispatch("account/getAccount");
				await context.dispatch("getData");
			};		
			
		
			initialization();
		},


		async getData( context, params ) { return FW.action.common( context, params, "get/data" ) },	


		async patchTemplates( context, params ) {	return FW.action.common( context, params, "patch/templates", "params" ) },
		async postTemplates( context, params ) { return FW.action.common( context, params, "post/templates" ) },
		async deleteTemplates( context, params ) { return FW.action.common( context, params, "delete/templates", "params" ) },		


		
		async postAuth( context, params ) {
			await context.dispatch("account/postAuth", params );
			await context.dispatch("init" );
		},		
		
	}
	
});


