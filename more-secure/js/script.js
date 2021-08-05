//alert("Javascript is enabled");
var flag = false;
if (flag == true)
{
	document.getElementById("submit").disabled = true;
}
function verify()
{
	let pass = document.getElementById("pass").value;
	let vpass = document.getElementById("vpass").value;
	if(pass == vpass)
	{
		document.getElementById("submit").disabled = false;
		document.getElementById("verify").className = 'hide';

	}
	else
	{
		document.getElementById("verify").className = 'show-pass';
		document.getElementById("submit").disabled = true;
	}
}