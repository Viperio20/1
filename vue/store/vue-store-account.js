"use strict";

const store_account = {
	namespaced: true,
	
	modules: {
		alert: store_alert,
	},		
	
	state: {
		auth: undefined,
		login: undefined,
		role: undefined,
		name: undefined,
		id: undefined,
		
		data: {},
	},
	
	getters: {
		auth: state => { return state.auth },
		login: state => { return state.data ? state.data.login : state.login },
		role: state => { return state.data ? state.data.role : state.role },
		name: state => { return state.data ? state.data.name : state.name },
		id: state => { return state.data ? state.data.id : state.id },
		
		data: state => { return state.data },
	},
	
	
	mutations: {
		setRole( state, data ) { state.data.role = data  },		
		setData( state, data ) {  state.data = data   },		
		patchData( state, data ) { for ( let key in data ) Vue.set( state.data, key, data[ key ]); },		
	},
	
	actions: {
		
		getAccount( context ) {
			
			return new Promise((resolve, reject) => {
				
				axios.get( "auth" ).then(response => {
					if ( response.data.success && response.data ) {
						let data = response.data;
						
						context.state.auth = data.auth !== undefined ? data.auth : false;
						
						if ( context.state.auth ) {
							// если на странице логина, то переадресовывваем на главную
							//if ( router.currentRoute.path.indexOf( "/login" ) != -1 ) router.push( "/" );				
							context.commit("setData", data);
							
						} else {
							// редирект на логин для неавторизованных
							//if ( router.currentRoute.path.indexOf( "/login" ) == -1 ) router.push("/login");				
						}
						
						resolve(data);
					} else { context.dispatch("error", response.data.errors[0] ); reject( response.data.errors[0] ); };
				})	.catch(error => reject(error))		
			
			});
		},
		
		
		postAuth( context, params ) {
			// authorization 
			return new Promise((resolve, reject) => {

				if ( !utils.check_required_params( ["login", "password"], params) ) resolve( false );  
				
				
				axios.post( "/auth", params ).then(response => {
					if ( response.data.success ) {
						
						context.dispatch("success", { message: "Ласкаво просимо до CRM!"});
						router.push("/");
						resolve( response.data );
						// change location href after login
						// window.location.href = "/"; -------- on home page after login
						//window.location.href = "/account";

					} else { context.dispatch("error", response.data.errors[0] ); reject(response.data.errors[0]) }
				})	.catch(error => { reject(error) })		
				
			});
		},
		
		
		
		deleteAuth( context ) {
			console.log( 'deleteAuth' );
			axios.delete( "/auth" ).then(response => {
				console.info( response );
				if ( response.data.success ) {
					context.state.auth = false;
					
					location.href = "/";	
					
				} else context.dispatch("error", response.data.errors[0] );
			})	.catch(error => { console.error(error) })		
		},		
		
		
		
	},
}