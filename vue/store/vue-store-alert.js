"use strict";

const store_alert = {
	
	state: {
		//awn: new AWN({position: "top-right"}),
		awn: new AWN( {} ),
	},

	actions: {
		
		notification( context, note ) {
			let 
				awn = context.state.awn, 
				options = { labels: {}, durations: {} },
				message = note.message ? note.message : " ";
			
			if ( note.name !== undefined  ) options.labels[ note.type ] = note.name;
			if ( note.duration !== undefined ) options.durations[ note.type ] = note.duration;
			
			awn[ note.type ]( message, options );	
		},

		error( context, note ) {
			let options = ( typeof note === 'string' || note instanceof String ) ? { message: note } : note; 
			options.type = "alert";
			context.dispatch( "notification", options );
		},	
		
		tip( context, note ) {
			let options = ( typeof note === 'string' || note instanceof String ) ? { message: note } : note; 
			options.type = "tip";
			context.dispatch( "notification", options );
		},	
		
		success( context, note ) {
			let options = ( typeof note === 'string' || note instanceof String ) ? { message: note } : note; 
			options.type = "success";
			context.dispatch( "notification", options );
		},	
		
		warning( context, note ) {
			let options = ( typeof note === 'string' || note instanceof String ) ? { message: note } : note; 
			options.type = "warning";
			context.dispatch( "notification", options );
		},	
		
		info( context, note ) {
			let options = ( typeof note === 'string' || note instanceof String ) ? { message: note } : note; 
			options.type = "info";
			context.dispatch( "notification", options );
		},		
	}
	
	
}