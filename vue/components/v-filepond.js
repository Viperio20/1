"use strict";

Vue.component('v-filepond', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {

		value: {  },
		id: { type: String, required: true },
		title: { type: String, default: `<i class='icon-plus-circle2 text-info mr-1'></i>Додати`}, 
		
		max_files: { type: Number, default: 1	 },
		img_preview: { type: Boolean, default: true },
		avatar: { type: Boolean, default: false },
		log: { default: false	 },
		
		accepted: { type: String },
		accepted_types: { type: Array },
		
		
		
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {
			pond: null,
			is_inner_update: false,
		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {
	

		files() {
			
			var 
				files = [],
				sources = this.value;
				
			if ( !Array.isArray( sources ) ) sources = [ sources ];
			
			sources.forEach( source_id => {

				if ( +source_id == source_id ) {
					
					files.push({
						source: source_id,
						options: {
							type: 'local'
						}						
					});
					
				} else {
					let type = typeof source_id ;
					if ( this.log ) console.error(`wrong type="${type}" for source_id = \"${source_id}\"`);	
				}
			});			
			
			return files;
		},

	},
	
	
	methods: {
		
		updateValue() {
			let value = ( this.max_files == 1 ) ? null : [];
			
			this.pond.getFiles().forEach( item => { 
			
				//value.push({ id: item.serverId, name: item.filename }) 	
				if ( this.max_files == 1 ) {
					value = item.serverId;
				} else {
					value.push( item.serverId ); 	
				};
			});
			
			if ( value !== this.value ) {
				this.$emit('input', value);
				this.is_inner_update = true;
			};
			
			if ( this.log ) console.info( this.value );
		}
		
	},
	
	
	beforeDestroy() {
		FilePond.destroy( this.$refs.filepond );
		// this.pond.destroy( this.$refs.filepond );
	},
	
	
	updated() {
		if ( this.log ) console.log("updated");
		if ( this.log ) console.info( this.value );
		
	},
	
	
	mounted() {
		
		var 
			self = this,
			vueEl = self.$refs.filepond;
		
		let pond_config = {
			labelIdle: this.title,
			server: {
				url: '/fileponder.php?filepond',
				process: { 	
					onload: (response) => { 
						console.info( response );
						return response 
					}, 
				},
			},
			
			allowImagePreview: this.img_preview,
			
			/*
			allowPdfPreview: true,
			pdfPreviewHeight: 320,
			pdfComponentExtraParams: 'toolbar=0&view=fit&page=1' ,
				
			pdfConvertType: 'image/png',
			pdfConvertMarginHeight: 60,			
			*/
			
			
			onprocessfile: (error, file) => { 
				//console.log('onprocessfile', this.value);
				this.updateValue()  
			},			
			onupdatefiles: (files) => { 
				//console.log('onupdatefiles');
				//this.updateValue() 
			},
			onremovefile: () => {
				//console.log('onremovefile');
				this.updateValue() ;
			},
			// лечим баг с undefined для имени файла
			onaddfile: (error, file) => {
				self.$emit('addfile', file, error );
				//document.querySelector('#' + self.id +' .filepond--file-info-main').childNodes[1].data = file.filename;
				/*
				Array.from(document.querySelectorAll('.filepond--file-info-main'))
					.filter(e => {
						return e.textContent === file.serverId
					})
					.forEach(e => e.childNodes[1].data = file.filename)
				*/
			},
		};
		
		// допустимые типы
		if ( this.accepted ) {
			switch ( this.accepted ) {
				case "image": case "images": case "img":
					pond_config.acceptedFileTypes = [ "image/\*" ];
				break;
				case "document": case "documents": case "doc":
					pond_config.acceptedFileTypes = [ "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ];
				break;
				case "pdf": 
					pond_config.acceptedFileTypes = [ "application/pdf" ];
				break;
				
				default:
					console.error( `Wrong accepted="${accepted}"` );
			}
		} else if ( this.accepted_types) {
			pond_config.acceptedFileTypes = this.accepted_types;
		};
		
		
		if ( this.avatar ) {
			
			
			pond_config.labelButtonDownloadItem = 'custom label'; // by default 'Download file'
			pond_config.allowDownloadByUrl = true; // by default downloading by URL disabled			
			
			
			pond_config.imagePreviewHeight = 170;
			pond_config.imageCropAspectRatio = '1:1';
			pond_config.imageResizeTargetWidth = 200;
			pond_config.imageResizeTargetHeight = 200;
			
			pond_config.stylePanelLayout = 'compact circle';
			pond_config.styleLoadIndicatorPosition = 'center bottom';
			pond_config.styleProgressIndicatorPosition = 'right bottom';
			pond_config.styleButtonRemoveItemPosition = 'right top';
			pond_config.styleButtonProcessItemPosition = 'right bottom';				
			
		};
		
		
		/*
		var 
			files = [],
			sources = this.value;
		if ( !Array.isArray( sources ) ) sources = [ sources ];
		
		sources.forEach( source_id => {

			if ( +source_id == source_id ) {
				
				files.push({
					source: source_id,
					options: {
						type: 'local'
					}						
				});
				
			} else {
				let type = typeof source_id ;
				if ( this.log ) console.error(`wrong type="${type}" for source_id = \"${source_id}\"`);	
			}
		});
		*/
		
		if ( this.files.length > 0 ) pond_config.files = this.files;
		
		
		this.pond = FilePond.create( this.$refs.filepond, pond_config );	

		

	},

/*
			data-allow-reorder="true"
			data-max-file-size="3MB"
*/


	watch: {
		value: function ( val, old_val ) {
			
			if ( !this.is_inner_update ) {
				if ( this.files.length > 0 ) this.pond.setOptions( { files: this.files });
				this.is_inner_update = false;
			};
			
		},
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				template
	template: `

			<input type="file"
				:id="id" 
				ref='filepond'
				:multiple=" max_files > 1 "
				:data-max-files="max_files"		
			>

	`
});


