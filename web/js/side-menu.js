$(document).ready(function () {

$(".leave-history").click(function(){
        window.location= location.origin + "/auth/leave-history";
		});
$(".side-menu-dashboard-link").click(function(){
        window.location= location.origin + "/auth/dashboard";
		});
$(".side-menu-apply-leave").click(function(){
        window.location= location.origin + "/auth/apply-leave";
		});
$(".side-menu-see-assets").click(function(){
        window.location= location.origin + "/auth/allAssets";
		});
$(".side-menu-apply-asset").click(function(){
        window.location= location.origin + "/auth/applyAsset";
		});
$(".respond-leave-request").click(function(){
        window.location= location.origin + "/auth/respondLeave";
		});
$(".respond-asset-request").click(function(){
        window.location= location.origin + "/auth/respond-asset-request";
		});
$(".side-menu-salary-account").click(function(){
        window.location= location.origin + "/auth/accountDetails";
		});
$(".all-salary-account").click(function(){
        window.location= location.origin + "/auth/all-salary-accounts";
    });

$(".side-menu-view-requests").click(function(){
        window.location= location.origin + "/auth/view-requests";
		});
$(".side-menu-raise-request").click(function(){
        window.location= location.origin + "/auth/raise-request";
		});
$(".side-menu-respond-request").click(function(){
        window.location= location.origin + "/auth/respond-request";
		});
$(".side-menu-view-paySlip").click(function(){
        window.location= location.origin + "/auth/paySlips";
		});
$(".side-menu-generate-paySlip").click(function(){
        window.location= location.origin + "/auth/Generate-Pay-Slips";
		});
$(".side-menu-claim-deduction").click(function(){
        window.location= location.origin + "/auth/deduction-claim";
		});
$(".side-menu-respond-claims").click(function(){
        window.location= location.origin + "/auth/respond-deduction-claim";
		});
$(".announcements-button").click(function(){
        window.location= location.origin + "/auth/announcements";
    });



$(".side-menu-update-employee").click(function(){
        window.location= location.origin + "/auth/update-employee";
		});
$(".side-menu-add-employee").click(function(){
        window.location= location.origin + "/auth/add-new-employee";
		});
$(".manage-attendance-button").click(function(){
        window.location= location.origin + "/auth/manage-attendance";
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