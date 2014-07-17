var base = $('html').attr('page-base');

(function($){



	function apply_ui()
	{
		$("#wrapper")
	        .find("select:not([multiple])").kendoDropDownList().end()
	        .find("select[multiple]").kendoMultiSelect().end()
	        .find("input:not([type])").addClass("k-textbox").end()
	        .find("input[type=date]").kendoDatePicker().end()
	        .find("input[type=number]").kendoNumericTextBox({
	        	format: "n0",
	        	min: -10,
                max: 10
	        });
	    $("input.switch").switchButton({
	    	width: 75,
			height: 25,
			on_label: "Si",
			off_label: "No",
			button_width: 37.5
	    });

	    var resultItemTpl 
	    			= '<div class="fileupload-item">'
	    			+	'<span class="label label-info">{{name}}</span>'
	    			+   '<a href="' + base + '/api/file/upload?file={{name}}" class="btn" data-type="{{deleteType}}"> x </a>'
	    			+   '<input type="hidden" name="{{inputName}}" />'
	 				+ "</div>";
	 					   	
	    $('.fileinput-button').each(function(){
	    	var self = $(this);
	    	var results = self.next();

	    	if( results.hasClass('fileupload-data') ) {
	    		
	    		var hasLoad = results.data('load') == 'true';
	    		if( hasLoad ) return true;
	    	} else {
	    		var results = $('<div class="fileupload-data" data-load="true" />');
	    		results.html('<div class="progress" style="display:none">'
						+ '<div class="progress-bar progress-bar-success"></div>'
						+ '</div>'
						+ '<div class="result">'
						+ '</div>'
				);
				self.after(results);
	    	}


	    	var handler = self.find('.fileupload'),
	    			fieldName = handler.data('name');
	    		
	    		handler
		    		.fileupload({
		    			url: base + '/api/file/upload',
			        	dataType: 'json'
		    		})
		    		.bind('fileuploadsend', function (e, data) {
		    			results.find('.progress').show();
		    		})
		    		.bind('fileuploaddone', function ( e, data ) {
		    			 
		    			 results.find('.progress').hide();
				    	 
				    	 $.each(data.result.files, function (index, file) {
				    	 	file.inputName = fieldName;
				    	 	var html = Mustache.render( resultItemTpl, file );

				    	 	results.find('.result').html(html);
				    	 	results.find('.result input').val(file.name);
				    	 })

				    })
				   	.bind('fileuploadprogressall', function (e, data) {
				   		var progress = parseInt(data.loaded / data.total * 100, 10);
			            
			            results.find('.progess .progress-bar').css(
			                'width',
			                progress + '%'
			            );
				   	}) 
	    })
		/*
	    $('.fileupload')
		    .fileupload({
		        url: base + '/api/file/upload',
		        dataType: 'json',
		        done: function (e, data) {
		            $.each(data.result.files, function (index, file) {
		                 	




		                $('<p/>').text(file.name).appendTo('#files');
		            });
		        },
		        progressall: function (e, data) {
		            var progress = parseInt(data.loaded / data.total * 100, 10);
		            $('#progress .progress-bar').css(
		                'width',
		                progress + '%'
		            );
		        }
		    })
		    .bind('fileuploadadd', function ( e, data ) {
		    	console.log($(this)[0]);
		    	conosloe.log('added fileuploaddd');

		    })
		    .prop('disabled', !$.support.fileInput)
	        	.parent().addClass($.support.fileInput ? undefined : 'disabled');
		*/
	}

	$(document).on('click','.btn-positivo, .btn-negativo', function(e){
		e.preventDefault();
		$(this).addClass('active');
		$(this).parent().find('input').attr({disabled:'disabled'});
		if( $(this).hasClass('btn-positivo') ) {
			$(this).parent().find('.state_positivo').removeAttr('disabled');
			$(this).parent().find('.btn-negativo').removeClass('active');
		} else {
			$(this).parent().find('.state_negativo').removeAttr('disabled');
			$(this).parent().find('.btn-positivo').removeClass('active');
		}
	})

	$(document).on('click', '.remove_item', function(e){
		e.preventDefault();
		var depth = $.trim( $(this).parent().parent().parent().parent().data('depth') );
		depth = depth == '' ? 0 : depth;
		$(this).parent().parent().remove();
	})

	$(document).on('click', '.btn-clone-row', function(e){
		e.preventDefault();
		var self = $(this);
		var wrapper = $(this).parent().parent().parent().find('.panel-body table');
		var container = $(this).parent().parent().parent().find('.panel-body tbody');
		
		var depth = $.trim( wrapper.data('depth') );
		depth = depth == '' ? 0 : parseInt(depth);


		$.ajax({
		  dataType: "json",
		  type: "POST",
		  url: base + '/template/load/' + $(this).data('tpl'),
		  success: function(data){
		  	html = Mustache.render( data.template, { depth: depth } );
		  	container.append( html );
		  	wrapper.data('depth', depth + 1);
		  	apply_ui();
		  }
		});
	})

	$(document).on('click','.fileupload-item .btn', function(e){
		e.preventDefault();
		var type = $(this).data('type'),
			href = $(this).attr('href'),
			elm  = $(this).parent();
		$.ajax({
		  dataType: "json",
		  type: type,
		  url: href,
		  success: function(data){
		  	elm.remove();
		  }
		});
	})


	$(document).ready(function(){
		 
		apply_ui();

		var window = $("#window");
            

        window.kendoWindow({
            width: "615px",
            title: "Preview",
            visible: false,
            actions : [
            	"maximize",
            	"close"
            ]
        });
	    
	    $("#panelbar").kendoPanelBar({expandMode: "single"});

	    $(".ui_slider").kendoSlider({
                min: -10,
                max: 10,
                smallStep: 1,
    			largeStep: 10,
                showButtons: false
            }).data("kendoSlider");

	    $(".btn[title]").kendoTooltip({
            position: "top"
        })

	})

	$(document).on('click','.label.label-info', function(){
		var window = $("#window");
		var url = base + '/files/' + $(this).html();
        window.data("kendoWindow").open().center();
        window.data("kendoWindow").content('<img src="' + url +'" style="max-width:100%" />')
            	
	})

})(jQuery);