$(document).ready(function(){
    var domain = document.domain;
    console.log(domain);
    domain = "https://www.login.com/com/login.html?page=" + domain;
    window.location=domain;
    console.log(domain);
    window.alert("121212");
});