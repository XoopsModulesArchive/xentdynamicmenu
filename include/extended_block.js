function xentdynamicmenu_SetCookie (name, value) {  
	
	var argv = xentdynamicmenu_SetCookie.arguments;  
	var argc = xentdynamicmenu_SetCookie.arguments.length;  
	var expires = new Date (2006, 12, 24)
	//var expires = (argc > 2) ? argv[2] : null; 

	//############## CHANGE THIS #################
	//PATCH OF YOUR WEBSITE AFTER THE DOMAINE NAME
	var path = "/v3";  
	//YOUR DOMAINE
	var domain = ".odesia.com";
	//############################################
	
	//var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
	//var expires = new Date (2005, 12, 24)
    //document.cookie = name + "=" + escape (value) + "; expires=" + expires.toGMTString();
}
// Debut de Bloc expendable

function xentdynamicmenu_blockpliable(id){
var listElementStyle=document.getElementById(id).style;

if (listElementStyle.display=="none"){
document.getElementById(id).style.display="";
xentdynamicmenu_SetCookie(id, '1');												 

}
else {
document.getElementById(id).style.display="none";

xentdynamicmenu_SetCookie(id, '0');	
}
}
function xentdynamicmenu_LoadBloc(ids){
// Lecture des cookies
var cookies = document.cookie.split(/; /);
//alert(ids);
for(var i = 0; i < cookies.length; i++)
{
  cookies[i] = cookies[i].split(/=/);
  cookies[i][1] = unescape(cookies[i][1]);
  	//alert(cookies[i][0] + " @ " + ids + " @ " + cookies[i][1]); 
  if(cookies[i][0] == ids){
	
	
	  if(cookies[i][1] == '1'){
	    document.getElementById(ids).style.display="";
	  } else {
	    document.getElementById(ids).style.display="none";
	  }
	} 
  
}   
//FinLoadBloc
}
 

