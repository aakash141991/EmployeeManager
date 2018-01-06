$(document).ready(function () {

		$('#displayTable').DataTable( {
                    "pagingType": "full_numbers",
                    "pageLength": 10
                } );
		$('#displayTableSecond').DataTable( {
                    "pagingType": "full_numbers",
                    "pageLength": 10
                } );

		$("#employee_search").keyup(function()
			   {
			   		var Url=location.origin + "/auth/searchEmployeesList";
			   		var text = document.getElementById('employee_search').value;
			       $.ajax({
			          type: "GET",
			          url: Url,
			          data: {'search_keyword' : text},
			          dataType: "text",
			          success: function(result)
			          {	
			          	var obj = $.parseJSON(result);
			          		if(obj['name'] != ""){
			          			$( "#suggest" ).html("");
			               		$( "#suggest" ).append( "<ul class='employee-suggestion-ul'><li class='employee-suggestion-li' data-id='"+obj['nid']+"'>"+obj['name'] + "  ( "+obj['nid']+")</li></ul>" );
			               		
			               		$( ".employee-suggestion-li" ).bind( "click", function() {
										  
									 document.getElementById('employee_search').value=$(this).attr('data-id') ; 
									 $( "#suggest" ).html("");
										  
										});

			               		$("#suggestions").html("");
			               		$( "#suggestions" ).append("<option value="+obj['nid']+">"+obj['name']+"</option>");

			          		}else{
			          			$( "#suggest" ).html("");
			          		}
			           		
			          }
			       });

			   });

		$("#admin_employee_search").keyup(function()
			   {
			   		var Url=location.origin + "/admin/searchEmployeesList";
			   		var text = document.getElementById('admin_employee_search').value;
			       $.ajax({
			          type: "GET",
			          url: Url,
			          data: {'search_keyword' : text},
			          dataType: "text",
			          success: function(result)
			          {	
			          	var obj = $.parseJSON(result);
			          		if(obj['name'] != ""){
			          			$( "#suggest" ).html("");
			               		$( "#suggest" ).append( "<ul class='employee-suggestion-ul'><li class='employee-suggestion-li' data-id='"+obj['nid']+"'>"+obj['name'] + "  ( "+obj['nid']+")</li></ul>" );
			               		
			               		$( ".employee-suggestion-li" ).bind( "click", function() {
										  
									 document.getElementById('admin_employee_search').value=$(this).attr('data-id') ; 
									 $( "#suggest" ).html("");
										  
										});
			          		}else{
			          			$( "#suggest" ).html("");
			          		}
			           		
			          }
			       });

			   });

		
		$(".form-submit").click(function(){
		
			//$(".page-loading").css('display','block');
		});
		
		



});

	$(window).load(function() {
		// Animate loader off screen
		$(".page-loading").delay(500).fadeOut("slow");;
	});