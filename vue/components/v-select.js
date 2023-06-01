"use strict";

Vue.component('v-select', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {
		label: { required: true },
		id: { type: String, required: true },
		value: { required: true },		
		name: {  },
		options: {},
		required: { default: true },
		/*

		
		type: { default: "text" },
		required: { default: "false" },
		
		
		error: { default: undefined }
		*/
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
	
<div class="form-floating">
	<select 
		class="form-select" 
		:id="id" 
		:value="value"
		:name="name"
		:required="required"
		v-on:change="$emit('input', $event.target.value)" 		
	>
	
		<slot></slot> 
		<template v-if="options">
			<option value="" hidden></option>
			<option v-for=" item in options " :key="item.id" :value="item.id">
				{{item.title}}
			</option>
		</template>
	</select>
	<label :for="id">{{label}}</label>
</div>	
	
`
});


