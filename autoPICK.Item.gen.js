//*************************My JavaScrip jsfile.jst****************

var httpobject;

function getInfo(str, length)
{
	if(str.length != 0)
	{
		httpobject=GetHttpObject();
		if (httpobject !=null)
		{
			var url="products.pick.gen.php";
			url=url+"?str="+str;
			url=url+"&length="+length;
			//walert(url);
			httpobject.onreadystatechange=stateChanged;
			httpobject.open("GET", url, true);
			httpobject.send(null);
		}
	}
	else
	{
		document.getElementById("autoSuggestionsList").innerHTML="";
	}
}

function stateChanged()
{
	if (httpobject.readyState==4)
	{
		document.getElementById("autoSuggestionsList").innerHTML=httpobject.responseText;
	}
}

function GetHttpObject()
{
	if (window.ActiveXObject) 
  		return new ActiveXObject("Microsoft.XMLHTTP");
	else if (window.XMLHttpRequest) 
		return new XMLHttpRequest();
	else 
	{
		alert("Your browser does not support AJAX.");
		return null;
	}
}
