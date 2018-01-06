$(document).ready(function () {
	
	$(".side-menu-dashboard-link").click(function(){
         window.location= location.origin + "/admin/dashboard";
		});
	$(".respond-leave-request").click(function(){
        window.location= location.origin + "/admin/respond-leave";
		});
$(".respond-asset-request").click(function(){
        window.location= location.origin + "/admin/respond-asset-request";
		});
$(".side-menu-respond-request").click(function(){
        window.location= location.origin + "/admin/respond-request";
		});

$(".all-salary-account").click(function(){
        window.location= location.origin + "/admin/all-salary-account";
    });
$(".side-menu-generate-paySlip").click(function(){
        window.location= location.origin + "/admin/generate-payslip";
		});
$(".side-menu-update-employee").click(function(){
        window.location= location.origin + "/admin/update-employee";
		});
$(".side-menu-add-employee").click(function(){
        window.location= location.origin + "/admin/add-new-employee";
		});
$(".manage-attendance-button").click(function(){
        window.location= location.origin + "/admin/manage-attendance";
		});
$(".announcements-button").click(function(){
        window.location= location.origin + "/admin/manage-announcements";
    });

$(".update-department").click(function(){
        window.location= location.origin + "/admin/update-department";
		});


$(".update-designations").click(function(){
        window.location= location.origin + "/admin/update-designation";
		});


$(".side-menu-update-leaves").click(function(){
        window.location= location.origin + "/admin/update-leaves";
		});
	

$(".side-menu-update-roles").click(function(){
        window.location= location.origin + "/admin/update-roles";
		});
$(".update-tax-slab").click(function(){
        window.location= location.origin + "/admin/update-tax-slab";
		});
$(".open-mobile-menu").click(function(){
       
       var classes =   $(this).attr('class');
       if(classes.indexOf('glyphicon-menu-hamburger') > -1){
       		$(this).removeClass('glyphicon-menu-hamburger');
       		$(this).addClass( 'glyphicon-remove');
       		$('.side-menu-wrapper').css('display','block');
       }else{
       		$(this).removeClass('glyphicon-remove');
       		$(this).addClass( 'glyphicon-menu-hamburger');
       		$('.side-menu-wrapper').css('display','none');
       }

		});



});


