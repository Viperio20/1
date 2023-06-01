"use strict";

Vue.component('b-acceptance-products', {
	
	data: function () {
		return {}
	},	
	
	props: {
		acceptance_id: {  },
		
	},
	
	mixins: [globalMixin],
	
	computed: {
		
		acceptance_products() {
			return this.$store.getters.acceptance_products.filter( item => item.acceptance_id == this.acceptance_id );
		},
		
		
		def_table_acceptance_products() {  
			let def = {
				columnDefs: [
					{"name": "id", "title": "№", "width": "1%", "render": Delphi.dt.render.id},
					{"name": "title", "title": "Найменування", "render": Delphi.dt.render.cell},
					{"name": "count", "title": "Кількість", "render": Delphi.dt.render.cell},
					// {"name": "count_left", "title": "Залишок", "render": Delphi.dt.render.cell},
					{"name": "price_one", "title": "Ціна за штуку", "render": Delphi.dt.render.number},
					{"name": "price_total", "title": "Загальна ціна", "render": Delphi.dt.render.number},
					{"name": "currency_id", "title": "Валюта", "render": function( data, type, row, meta ) { 
 							let value = store.getters.currency.find( item => item.id == data );
							if ( value ) return `<div data-id="${row.id}" data-fw-push="">${value.title}</div>`;	
							else return `<div data-id="${row.id}" data-fw-push=""></div>`;	 
 						}, 
	},
				],
				"order": [[ 0, "desc" ]],
				createdRow: function( row, data, dataIndex ) {
					$(row).find("[data-fw-push]").attr("data-fw-push", "/acceptance_products-edit");
				},
			};
			
			return def;
		}
	},
	
	methods: {
	
		getSelectedIds() {
			var ids = [];
			$( ".delphi-id:visible:checked" ).each(function( index, el ) {
				ids.push( $(el).attr("data-id") );
			});			
			return ids;	
		},
	
		async copyRows() {
			let res = await FW.action.request( { ids: this.getSelectedIds(), entity: "acceptance_products" }, "post/copy/rows" );
			if ( res.success ) {		
				res.data.forEach( item => {
					this.$store.commit("postAcceptance_products", item);
				});
			};
			return false;
		},			
	
		async deleteRows() {
			if ( confirm( "Ви впевнені, що хочете видалити ці рядки?" )) {
				let
					ids = this.getSelectedIds(),
					res = await FW.action.request( { id: ids }, "delete/acceptance_products" );
				if ( res.success ) {		
					ids.forEach( id => {
						this.$store.commit("deleteAcceptance_products", { id: id});
					});
				};
			};
			return false;
		},

		async updateRows( name, value_id ) {
			
			let
				ids = this.getSelectedIds(),
				send_params = { id: ids  };
				
			send_params[ name ] = value_id;		
				
			let res = await FW.action.request( send_params , "patch/acceptance_products" );
			if ( res.success ) {		
				ids.forEach( id => {
					let upd_params = { id: id};
					upd_params[ name ] = value_id;
					this.$store.commit("patchAcceptance_products", upd_params);
				});
			};
			
			return false;
		},
	
	},
	
	template: `
	
<div class="content">
	<div class="row">
		
		<div class="card" style="width: 100%">
			<div class="card-header bg-light header-elements-inline">
				<h5 class="card-title">Доданi товари</h5>
				<div class="header-elements">
					<button :data-fw-push=' "/acceptance_products-add?acceptance_id=" + acceptance_id ' type='button' class='btn btn-light btn-white mr-2 border-info'>
						<i class='icon-plus-circle2 text-info mr-1'></i>Створити
					</button>					

					
					<input id="delphi-id-count-selected" value="0" class="form-control" />
					
					<div class="btn-group justify-content-center mr-2">
						<a href="#" class="delphi-btn-rows-change disabled btn btn-light btn-white dropdown-toggle" data-toggle="dropdown" >Змінити</a>

						<div class="dropdown-menu">
							<a @click.prevent="copyRows" href="#" class="dropdown-item">Копіювати</a>
							<a @click.prevent="deleteRows" href="#" class="dropdown-item">Видалити</a>
						</div>
					</div>

					
	<div class="btn-group justify-content-center">
		<a href="#" class="delphi-btn-rows-change disabled btn btn-light btn-white dropdown-toggle" data-toggle="dropdown">Валюта</a>

		<div class="dropdown-menu">
			<a @click.prevent="updateRows('currency_id',  item.id )" v-for=" item in $store.getters.currency " :key=" item.id " href="#" class="dropdown-item">{{item.title}}</a>
		</div>
	</div>
	
					<!--
					<div class="btn-group justify-content-center">
						<a href="#" class="delphi-btn-rows-change disabled btn btn-light dropdown-toggle" data-toggle="dropdown">Статус</a>

						<div class="dropdown-menu">
							<a href="#" class="dropdown-item">Action</a>
							<a href="#" class="dropdown-item">Another action</a>
							<a href="#" class="dropdown-item">Something else here</a>
						</div>
					</div>
					-->
							
							
				</div>
			</div>
			
			<div class="card-body">

					<v-datatable
						id = "acceptance_products_table"
						entity = "acceptance_products"
						:value = "acceptance_products"
						:def = "def_table_acceptance_products"
					></v-datatable>
				
			</div>
		</div>	
	
	</div>
</div>	
`
});


