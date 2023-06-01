"use strict";

Vue.component('v-select2', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {
		options: { required: true },	
		value: { required: true },
		config: { type: Object, default: function() { return {} } },
		db: { type: Object, default: function() { return {} } },
		required: { default: false },
		
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {
			selected: undefined,
		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {
		
		db_calc() {
			let config = this.db;
			
			if ( config.field_id === undefined ) config.field_id = "id";
			if ( config.field_text === undefined ) config.field_text = "title";
			
			if ( config.join === undefined ) config.join = {};
			else if ( config.join.entity !== undefined ) {
				if ( config.join.url_post === undefined ) config.join.url_post = "/" + config.join.entity + "-add";
				if ( config.join.url_patch === undefined ) config.join.url_patch = "/" + config.join.entity + "-edit";
			};
			
			return config;
		}, 
		
		options_calc() {
			let db = this.db_calc;
			return this.options.map( (item) => { return { id: item[ db.field_id ], text: item[ db.field_text ] } });
		},
		
		el() { return $( this.$refs.sel2 ) },
		
		config_calc() {
			let config = this.config;
			
			config.data = this.options_calc;
			
			// placeholder
			if ( config.placeholder === undefined ) config.placeholder = "Виберіть значення";
			
			// soring
			if ( config.sortResults === undefined ) config.sortResults = data => data.sort((a, b) => a.text.localeCompare(b.text));
			
			// language
			if ( config.language === undefined ) config.language = {};
			if ( config.language.noResults === undefined ) config.language.noResults = () => "Не знайдено результатів";
			
			return config;
		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				methods
	methods: {
		
		setValue( value ) {
			console.info( "Select2 value was changed to = ", value);
			this.el.val(value).trigger("change");
		},
		
		refreshOptions() {
			if ( this.options === undefined || this.options.length == 0 ) return false;
			this.el.empty().select2( this.config_calc );
			this.setValue( this.value );
		},
		
		goToJoin( url ) {
			//localStorage.setItem('delphi.join_url', url);
			//localStorage.setItem('delphi.join_curr_select_id', this.value);
		},
		
		
	},	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				mounted
	mounted() {
		//console.log("Select2 mounted");	
		
		//console.info( "prev url = ", this.$store.prev_route_path, localStorage.getItem('delphi.join_url') );
		
		
		
		var vm = this;
		
		vm.el.select2({}).on("change", function () {
			vm.$emit("input", this.value);
			vm.selected = this.value;
			// console.log("select2 was changed");
		});

		this.refreshOptions();
		
		//if ( this.$store.prev_route_path == localStorage.getItem('delphi.join_url') && localStorage.getItem('delphi.join_curr_select_id') ) this.setValue( localStorage.getItem('delphi.join_curr_select_id') );
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				watch
	watch: {
		value: function (value) { this.setValue( value ); },
		options: function (options) { this.refreshOptions(); }
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				destroyed
	destroyed() {
		//console.log("Select2 destroyed");	
		this.el.off().select2("destroy");
	},	
	

	template: `

	
	
<div v-if=" db_calc.join" class='input-group'>
	<select ref="sel2" class="form-control select" :required="required">
		<option></option>
		<slot></slot>						
	</select>
	<span class='input-group-append'>
		<button @click="goToJoin( db_calc.join.url_post )" v-if=' !value ' :data-fw-push = ' db_calc.join.url_post ' type='button' class='btn btn-link btn-link-icon'>
			<i class='icon-plus-circle2 text-info'></i>
		</button>
		<button v-else @click="goToJoin( db_calc.join.url_patch + '/' + value )" :data-id='value' :data-fw-push=' db_calc.join.url_patch ' type='button' class='btn btn-link btn-link-icon'>
			<i class='icon-pencil text-success'></i>
		</button>
	</span>
</div>		
	
<select v-else ref="sel2" class="form-control select" :required="required">
	<option></option>
	<slot></slot>
</select>	
`
	
});








