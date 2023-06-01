
"use strict";

const TemplatesDetailPage = {
	
	mixins: [globalMixin],
	props: ['method'],
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data	
	data: function () {
		return {
			curr_templates: undefined,
			modifications_author: undefined,
			server_response: "wait",
			fields: [],
		}
	},	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed	
	computed: {
		item_id() { return this.$route.params.item_id; },
		account() { return this.$store.getters["account/data"] },	
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				methods	
	methods: {
		saveForm() {
			console.log("saveForm");
			this.$store.dispatch( this.method + "Templates", this.curr_templates );
			router.go(-1)
		},	
		
		back() {
			router.go(-1);
		},
		
		remove() { 
			if ( confirm( "Ви впевнені, що бажаєте видалити цю позицію?" )) {
				this.$store.dispatch("deleteTemplates", { id: this.item_id});
				router.go(-1);
			}
		},
		
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				created	
	async created() {

		if ( this.method == "post" ) {
			this.curr_templates = {title: '', };
			this.server_response = "success";
		} else {
		
			console.info( { id: this.item_id} );
		
			let res = await FW.action.request( { id: this.item_id}, "get/templates/detail" );
			if ( res.success && res.data ) {
			
				if ( res.data.fields ) {
					this.fields = res.data.fields;
					this.server_response = "success";
				};
				
				
			} else {
				this.server_response = "fail";
			}
			
		}
	},


	template: `
<div class="card">

	
	<div v-if="fields" class="card-header d-sm-flex align-items-sm-center py-sm-0">
		<h6 class="py-sm-3 mb-sm-auto">Створити документ</h6>
		<div class="ms-sm-auto my-sm-auto">

			<button class="btn legitRipple mr-2 btn-light btn-white border-success" form="createFormTemplates">
				<i class="ph-check-circle  mx-1 text-success"></i>Сгенерувати
			</button>	
			
			<button @click.prevent="back" type="button" class="btn btn-light btn-white mr-2 egitRipple">
				<i class="ph-arrow-clockwise mx-1 "></i>Закрити
			</button>	

		</div>
	</div>	
	
	

	<div v-if="fields" class="card-body">
		<form action="" method="POST" id="createFormTemplates">

				
					<input type="hidden" name="template_id" :value="item_id" />			
					<div v-for=" field in fields" class="row mb-3">
						<label class="col-lg-2 col-form-label">{{field.title}}:</label>
						<div class="col-lg-10">
							<input :name=" 'fields[' + field.name + ']' " required type="text" class="form-control" placeholder="">
						</div>
					</div>								


		</form>
	</div>
</div>`
	
};
