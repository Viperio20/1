
const utils = {
	
	vars: {
		fn_export: "Example",
	},
	tables: {},
	render: {
		
		datetime( data, type, row, meta ) {	
			if ( type !== 'display' ) return data;
			
			let 
				mom_time = moment( data ).format('HH:mm'),
				mom_date = moment( data ).format('DD.MM.YYYY');
			
			return `
				<div class="d-block">
					${mom_time}
					<div class="text-shades-2 fs-10">${mom_date}</div>
				</div>				
			`;	
		},			

		
		
		// $(row).attr( "data-id", data.id ).attr("data-fw-push", "/'. $comp["table"]["entity"] .'-edit");
		
	},
	
	check_required_params( params, data ) {	
		
		let res = true;
		
		params.forEach( ( param_name ) => {
			if ( data[ param_name ] === undefined ) {
				console.error( `${param_name} is required` );
				res = false;
			}
		});
	
		return res;
	},
	
	
};










const datatable_scheme = {
	columnDefs: [],
	stateSave: true,
	responsive: true,
	autoWidth: false,
	
	"bStateSave": true,
	"fnStateSave": function (oSettings, oData) {
		localStorage.setItem('offersDataTables', JSON.stringify(oData));
	},
	"fnStateLoad": function (oSettings) {
		return JSON.parse(localStorage.getItem('offersDataTables'));
	},	
	

	buttons: {            
		dom: {
			button: {
				className: 'btn btn-light'
			}
		},
		buttons: [
		
		{
			extend: 'print',
			text: '<i class="icon-printer mr-2"></i> Print',
			className: 'btn bg-danger ml-2',
			exportOptions: { orthogonal: "export" },
		},
		{
			extend: 'excel',
			text: '<i class="icon-file-excel mr-2"></i> Excel',
			className: 'btn bg-green ml-2',
			exportOptions: { orthogonal: "export" },
		},				
		{
			extend: 'pdf',
			text: '<i class="icon-file-pdf mr-2"></i> PDF',
			className: 'btn bg-orange ml-2',
			exportOptions: { orthogonal: "export" },
		},				
		]
	},
	


	dom: '<"datatable-header"flB><"datatable-scroll-wrap"t><"datatable-footer"ip>',
};



$(document).ready(function()  {
	$.extend( $.fn.dataTable.defaults, datatable_scheme );
})



