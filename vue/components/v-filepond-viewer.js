"use strict";

Vue.component('v-filepond-viewer', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {

		id: { type: String },
		value: {  },
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {
			src: undefined,
			type: undefined,
		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {
		


	},
	
	
	methods: {
		
		async getFilepond() {
			
			if ( !this.value ) return false;
			
			let res = await FW.action.request( { id: this.value }, "get/filepond/resource" );			
			if ( res.data.res ) {
				this.src = res.data.src;
				this.type = res.data.type;
			};
		},		
		
	},
	
	
	
	
	updated() {
	
	},
	
	
	created() {
		
		this.getFilepond();	

	},

	// ~~~~~~~~~~~~~~~~~~~~~~				template
	template: `
		<div>
			<div v-if="src" >
				<a v-if=" type == 'application/pdf' " :href="src" target="_blank">
					<div style="background-image: url('/assets/images/pdf-icon-png-2056.png')" class="filepond-preview"></div>
				</a>
				<a v-else :href="src" target="_blank">
					<div :style=" 'background-image: url(' + src + ')' " class="filepond-preview"></div>
				</a>
			</div>
			<div v-else>
				<div class="filepond-preview no-image">
					<label>Brak pliku</label>
				</div>
			</div>
		</div>
	`
});


