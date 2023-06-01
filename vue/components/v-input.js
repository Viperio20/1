"use strict";

Vue.component('v-input', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {
		id: { type: String, required: true },
		value: { required: true },
		type: { default: "text" },
		required: { default: false },
		name: {  },
		step: {  },
		min: {  },
		max: {  },
		

		
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {

		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {
		

	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				methods
	methods: {
	},	
	
	mounted() {
	},
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				template
	template: `
<div class="form-floating mb-3">
	<input 
		:id="id" 
		:type="type" 
		:value="value"
		:required="required"
		:name="name"
		:step="step"
		:min="min"
		:max="max"
		placeholder=""
		v-on:change="$emit('input', $event.target.value)" 
		class="form-control"
	>
	<label :for="id">
		<slot></slot>
	</label>
</div>		

`
});


