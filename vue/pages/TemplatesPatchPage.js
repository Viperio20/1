
"use strict";

const TemplatesPatchPage = {
	
	mixins: [globalMixin],
	props: ['method'],
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data	
	data: function () {
		return {
			curr_templates: undefined,
			modifications_author: undefined,
			server_response: "wait",
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
		
			let res = await FW.action.request( { id: this.item_id}, "get/templates" );
			if ( res.success && res.data ) {
			
				if ( res.data.templates ) {
					this.curr_templates = res.data.templates;
					this.server_response = "success";
				};
				
				if ( res.data.modifications_author ) {
					this.modifications_author = res.data.modifications_author;
				};
				
				
			} else {
				this.server_response = "fail";
			}
			
		}
	},


	template: `
<div class="card">

	<!--
	<div  v-if="curr_templates" class="card-header bg-light header-elements-inline">
		<h5 class="card-title">Додати шаблон</h5>
		<div class="header-elements">

			<button v-if=" method == 'post' " class="btn btn-success1 legitRipple mr-2 btn-light btn-white border-info" form="saveFormTemplates">
				<i class="icon-plus-circle2 mr-1 text-info"></i>Створити
			</button>	
			<button v-else class="btn legitRipple mr-2 btn-light btn-white border-success" form="saveFormTemplates">
				<i class="icon-paperplane mr-1 text-success"></i>
				Зберегти
			</button>	
			
			<button @click.prevent="back" type="button" class="btn btn-light btn-white mr-2 egitRipple">
				<i class="icon-undo2 mr-1 "></i>
				Закрити
			</button>	
			<button v-if=" method=='patch' " @click.prevent="remove" type="button" class="btn btn-warning1 btn-light btn-white legitRipple mr-2 ml-5 border-danger">
				<i class="icon-cross2 mr-1 text-danger"></i>
				Видалити 
			</button>	
			
			<div class="float-right">
			
			</div>
			
		</div>
	</div>
	-->
	
	<div v-if="curr_templates" class="card-header d-sm-flex align-items-sm-center py-sm-0">
		<h6 class="py-sm-3 mb-sm-auto">Додати шаблон</h6>
		<div class="ms-sm-auto my-sm-auto">

			<button v-if=" method == 'post' " class="btn btn-success1 legitRipple mr-2 btn-light btn-white border-info" form="saveFormTemplates">
				<i class="ph-plus-circle mxr-1 "></i>Створити
			</button>	
			<button v-else class="btn legitRipple mr-2 btn-light btn-white border-success" form="saveFormTemplates">
				<i class="ph-check-circle  mx-1 text-success"></i>Зберегти
				
				
			</button>	
			
			<button @click.prevent="back" type="button" class="btn btn-light btn-white mr-2 egitRipple">
				<i class="ph-arrow-clockwise mx-1 "></i>Закрити
				
			</button>	
			<button v-if=" method=='patch' " @click.prevent="remove" type="button" class="btn btn-warning1 btn-light btn-white legitRipple mr-2 ml-5 border-danger">
				<i class="ph-x-circle  mx-1 "></i>
				Видалити 
			</button>	


		</div>
	</div>	
	
	

	<div v-if="curr_templates" class="card-body">
		<form @submit.prevent="saveForm" id="saveFormTemplates">

				
								
					<div class="row mb-3">
						<label class="col-lg-2 col-form-label">Найменування:</label>
						<div class="col-lg-10">
							<input v-model = 'curr_templates.title' required type="text" class="form-control" placeholder="">
						</div>
					</div>								

					<div class="row mb-3">
						<label class="col-lg-2 col-form-label">Посада:</label>
						<div class="col-lg-10">
							<input v-model = 'curr_templates.posada' required type="text" class="form-control" placeholder="">
						</div>
					</div>								

					<div class="row mb-3">
						<label class="col-lg-2 col-form-label">Шаблон:</label>
						<div class="col-lg-10">
							<textarea style="height: 350px;" v-model = 'curr_templates.text' required class="form-control" placeholder=""></textarea>
						</div>
					</div>								


		</form>
	</div>
</div>`
	
};
