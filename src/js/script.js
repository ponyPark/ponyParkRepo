/*BAM Software JS for PonyPark
October, 2013*/
function init() {
    function userLogged(){
	       var request = new XMLHttpRequest();
	       var url = 'userLogged.php';
	       var data;

	       request.open("GET", url, true);
	       request.send();
	       request.onreadystatechange = function(e) {

	      	if(request.readyState === 4){
	                //save the response from server
	                //if userLogged.php outputs false, then the signin will display
	                //if userLogged.php outputs true, then the favorites, logout, and manage account will display
	            console.log(request.responseText);
	       		data = request.responseText;
	       		if(data === "true"){
	        	$('#signIn').css('display','none');
	        	$('#userOptions').css('display','block');
	        	}
	        	if(data === "false"){
	        	$('#signIn').css('display','block');
	        	$('#userOptions').css('display','none');
	        	}

	      	}
	   	}
	        

    }


userLogged();
}
window.onload = init;
