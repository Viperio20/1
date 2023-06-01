"use strict";

const HomePage = {
	
	mixins: [globalMixin],
	
	data: function () {
		return {
			
		}
	},	
	
	
	
	
	computed: {
		role() { return this.$store.getters["account/role"] },
		
		def_table_templates() {  
			let def = {
				
				columnDefs: [
					{"name": "id", "title": "№", "width": "1%", "render": Delphi.dt.render.cell},
					{"name": "title", "title": "Назва", "render": Delphi.dt.render.cell},
					{"name": "posada", "title": "Посада", "render": Delphi.dt.render.cell},
				],
				"order": [[ 0, "desc" ]],
				createdRow: function( row, data, dataIndex ) {
					$(row).find("[data-fw-push]").attr("data-fw-push", "/templates-edit");
					// $(row).attr( "data-role", data.role );
				},
			};
			
			return def;
		},	
		
		def_table_user_templates() {  
			let def = {
				
				columnDefs: [
					{"name": "id", "title": "№", "width": "1%", "render": Delphi.dt.render.cell},
					{"name": "title", "title": "Назва", "render": Delphi.dt.render.cell},
				],
				"order": [[ 0, "desc" ]],
				createdRow: function( row, data, dataIndex ) {
					$(row).find("[data-fw-push]").attr("data-fw-push", "/templates-detail");
					// $(row).attr( "data-role", data.role );
				},
			};
			
			return def;
		},		
		
		
	},
	
	methods: {
		
	
		
	},

	template: `	
<div>	
	
	<div class="row">
		<div class="col-xl-12">
			
			<div class="card">
				<div class="card-header d-flex align-items-center">
					<h5 class="mb-0">Документи</h5>
					
					<div v-if=" role == 'admin'" class="ms-sm-auto my-sm-auto">
						<button data-fw-push='/templates-add' type="button" class="btn btn-primary">
							<i class="ph-plus me-2"></i>
							Додати
						</button>
					</div>					
				</div>

				<div class="card-body">
				
					<v-datatable v-if=" role == 'admin'"
						id = "templates_table"
						entity = "templates"
						:value = "$store.getters.templates"
						:def = "def_table_templates"
					></v-datatable>
					<v-datatable v-else
						id = "templates_user_table"
						entity = "templates"
						:value = "$store.getters.templates"
						:def = "def_table_user_templates"
					></v-datatable>				
				
				</div>
			</div>
			
		
		
		
		</div>
	</div>	
</div>
	`
	
};
