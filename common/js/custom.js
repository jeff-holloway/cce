$(document).ready(function() {
		$("#toggle").on("click", function() {
		$(".row-offcanvas").toggleClass("active");
		});
		  $('input[type="checkbox"]').click(function(){
		$(this).next().toggleClass('chk-active');
		
	})
		  
    });
			