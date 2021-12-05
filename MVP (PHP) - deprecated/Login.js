function ValidLogin(event)
{
	var emailInput = document.getElementById("email").value;
	var pswdInput = document.getElementById("password").value;
	
	var emailMsg = document.getElementById("emailMsg");
	var pswdMsg = document.getElementById("pswdMsg");
	
	emailMsg.innerHTML = "";
	pswdMsg.innerHTML = "";
	
	var emailCheck = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
	var pswdCheck = /^(\S*)?\d+(\S*)?$/;
	
	var checkResult = true;
	
	if(emailInput == null || emailInput == "" || !emailCheck.test(emailInput))
	{
		emailMsg.innerHTML = "Please enter valid email.";
		checkResult = false;
	}
	
	if(pswdInput == null || pswdInput == "" || pswdInput.length < 8 || !pswdCheck.test(pswdInput))
	{
		pswdMsg.innerHTML = "Password must be at least 8 characters.";
		checkResult = false;
	}
	
	if(checkResult == false)
	{
		event.preventDefault();
	}
}