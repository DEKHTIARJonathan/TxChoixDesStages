// All the global vars...
var service = window.location.origin + window.location.pathname;


function check_data(data) {
    $("#alert").hide(200).removeClass('alert-success').removeClass('alert-error')
    if(data.error) {
        console.log(data);
        $("#alert-title").html(data.error.title);
        $("#alert-content").html(data.error.content);
        $("#alert").show(300).addClass('alert-error');
        return false
    } else if (data.success) {
        $("#alert-title").html(data.success.title);
        $("#alert-content").html(data.success.content);
        $("#alert").show(300).addClass('alert-success');
        return true
    } else {
        $("#alert").hide(200);
        return true
    }
}

// SOURCE : http://jquery-howto.blogspot.fr/2009/09/get-url-parameters-values-with-jquery.html
$.extend({
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});

function add_logout_button() {
    $("#logout").show();
    $("#logout").click(function () {
        $.getJSON('api.php?function=logout', function(data) {
            if (check_data(data)) {
                cas_url = data.cas_url
                url = cas_url+'logout'+'?service='+service;
                window.location = url;
            }
        });
    });
}

function connection_button(data) {
    // Show a msg box with a link for connect the user 
    $("#lead").html("Veuillez vous connecter !");
    cas_url = data.cas_url
    url = cas_url+'login'+'?service='+service;
    $("#content").html("Pour pouvoir réaliser des voeux, vous devez vous authentifier.<br /> \
<a href=\""+url+"\" class=\"btn btn-large btn-info pull-right\" id=\"cas-connection\"> Me connecter </a>");
}

function is_logged() {
    $.getJSON('api.php?function=is_logged', function(data) {
        check_data(data);
        if(data.logged) {
            run()
        } else {
            connection_button(data)
        }
    });
}

function register_login(ticket, service) {
    $.getJSON('api.php?function=register_login&ticket='+ticket+'&service='+service, function(data) {
        check_data(data);
        if(data.logged) {
            
        } else {
            connection_button(data)
        }
    });   
}

// All the logic :)
var ticket = $.getUrlVar('ticket');
if(ticket){
    console.debug("Validation du ticket "+ticket);
    register_login(ticket, service)
} else {
    is_logged();
}
