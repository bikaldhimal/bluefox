function checkPassword(){
  let password = document.getElementById("password").value;
  let cnfrmPassword = document.getElementById("cnfrm-password").value;
  console.log(password, cnfrmPassword);
  let message = document.getElementById("message");

  if(password.length != 0){
    if(password == cnfrmPassword){
      message.textContent = "Passwords matched";
      message.style.backgroundColor = "#3ae374";
    }else{
      message.textContent = "Passwords doesn't matched";
      message.style.backgroundColor = "#ff4d4d";
    }
  }else{
    alert("Passwords can't be empty!");
    message.textContent = "";
  }
}