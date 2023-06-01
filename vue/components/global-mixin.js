"use strict";

const globalMixin = {
	
	methods: {
		

		createPlugin() {
			
			// When content is loaded
			//App.initCore();
			//console.log('App.initCore()');			

			// When page is fully loaded
			//App.initAfterLoad();
			//console.log('App.initAfterLoad()');
			
		},
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				mounted
	mounted() {


		
		if (document.readyState === "complete") {
			$(document).ready( () =>  {
				this.createPlugin();
			});
		} else {
			$(document).ready( () =>  {
				this.createPlugin()
			});
		}	


	
	},	
}