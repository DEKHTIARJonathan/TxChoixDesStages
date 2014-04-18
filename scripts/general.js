// All the global vars...
var badge_id = "";
var service = window.location.origin + window.location.pathname;
var badgeuse_url = "http://localhost:8080/"
var min_version = 1
var tarifs = {}
var nb_place_total = 0
var prix_total = 0

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
    $("#content").html("Pour pouvoir vendre des billets, vous devez vous authentifier... <br /> \
<a href=\""+url+"\" class=\"btn btn-large btn-info pull-right\" id=\"cas-connection\"> Me connecter </a>");
}

function check_badgeuse(callback) {
    $.ajax({dataType: "json",
        url: badgeuse_url+'version',
        success: function(data) {
            if(!data.version) {
                $('#modal-header').html("Impossible de trouver la badgeuse")
                $('#modal-body').html("<p>Veuillez indiquer l'url de la badgeuse</p><p><div class=\"input-append\">\
  <input class=\"input-xlarge\" id=\"badgeuse_url\" type=\"text\" value=\""+badgeuse_url+"\">\
  <button class=\"btn\" type=\"button\" id=\"badgeuse_action\">Tester !</button>\
</div></p>")
                $('#modal').modal()
                $('#badgeuse_action').click(function () {
                    badgeuse_url = $('#badgeuse_url').val()
                    $('#modal').modal('hide')
                    check_badgeuse(callback)
                })
            } else if (data.version < min_version) {
                alert("Your badgeuse version is not uptodate... please update...")
            }
            callback()
        },
        error: function() {
            $('#modal-header').html("Impossible de trouver la badgeuse")
            $('#modal-body').html("<p>Veuillez indiquer l'url de la badgeuse</p><p><div class=\"input-append\">\
<input class=\"input-xlarge\" id=\"badgeuse_url\" type=\"text\" value=\""+badgeuse_url+"\">\
<button class=\"btn\" type=\"button\" id=\"badgeuse_action\">Tester !</button>\
</div></p>")
            $('#modal').modal()
            $('#badgeuse_action').click(function () {
                badgeuse_url = $('#badgeuse_url').val()
                $('#modal').modal('hide')
                check_badgeuse(callback)
            })
        }
    });
}

function update_btn_acheter() {
    $(".btn-acheter").each( function() {
        var tarif_id = $(this).attr("tarif_id");
        if(tarifs[tarif_id].nb_cart < (tarifs[tarif_id].nb_max - tarifs[tarif_id].nb_buyed)) {
            $(this).removeClass("disabled")
            $(this).addClass("btn-success")
            $(this).removeClass("btn-inverse")
        } else {
            $(this).addClass("disabled")
            $(this).addClass("btn-inverse")
            $(this).removeClass("btn-success")
        }
            
    })
}

function payer(mode) {
   $.ajax({dataType: "json",
        url: 'api.php?function=payer',
        data: {badge_id: badge_id, mode: mode, tarifs: tarifs},
        success: function(data) {
            if(check_data(data)) {
                vente()
            }
        },
        error: function() {
        }
    }); 
}

function payer_payutc() {
    payer("payutc")
}

function payer_cash() {
    payer("cash")
}

function add_to_cart(tarif_id) {
    if(!$("#table_cart").html()) {
        $('<p class="lead"> Panier en cours </p>\
            <table class="table" id="table_cart">\
            <thead><th>Nom de l\'événement </th><th> Détails </th><th> Prix </th><th> Retirer </th></thead>\
            <tbody></tbody>\
            </table>\
           <p class="lead"> Validation </p>\
            <strong>Nombre de place dans le panier : </strong><span id="nb_place_total">0</span><br />\
            <strong>Prix total : </strong><span id="prix_total">0</span> €<br />\
            <a href=\"#\" class=\"btn btn-danger pull-right\" id=\"btn-annuler\" > Annuler </a>\
            <a href="#" class="btn disabled pull-right" id="btn-payutc" >Payer avec payutc</a>\
            <a href="#" class="btn pull-right" id="btn-cash" >Payer en espece</a>').appendTo($("#content"))
        $("#btn-annuler").click(function() { vente() })
        $("#btn-payutc").click(function() { payer_payutc() })
        $("#btn-cash").click(function() { payer_cash() })
        prix_total = 0
        nb_place_total = 0
    }
    if(tarifs[tarif_id].nb_cart < (tarifs[tarif_id].nb_max - tarifs[tarif_id].nb_buyed)) {
        tarifs[tarif_id].nb_cart += 1
        $("<tr><td>" + tarifs[tarif_id].event_name + "</td>\
               <td>" + tarifs[tarif_id].tarif_name + "</td>\
               <td>" + tarifs[tarif_id].price + " € </td>\
               <td><a href=\"#\" class=\"btn btn-retirer btn-danger\" t_id=\""+ tarif_id + "\" > Retirer </a></td>\
          </tr>").appendTo($("#table_cart tbody"))
        prix_total += tarifs[tarif_id].price
        nb_place_total += 1
        $(".btn-retirer").unbind("click").click(function() { 
            var t_id = $(this).attr("t_id")
            tarifs[t_id].nb_cart -= 1
            prix_total -= tarifs[t_id].price
            nb_place_total -= 1
            $(this).parent().parent().remove()
            update_btn_acheter() 
            $("#prix_total").html(prix_total) 
            $("#nb_place_total").html(nb_place_total)})
        $("#prix_total").html(prix_total)
        $("#nb_place_total").html(nb_place_total)
    } else {
        // Nothing
    }
    update_btn_acheter()
}

function vente() {
    $("#lead").html("Scannez un badge...")
    $("#content").html("")
    $.getJSON(badgeuse_url+'badge', function(data) {
        badge_id = data.badge_id;
        $("#lead").html("Récupération d'info en cours...")
        $("#content").html("Le badge scanné est le numero "+ badge_id)
        $.getJSON('api.php?function=info_badge&badge_id='+badge_id, function(data) {
            if (check_data(data)) {
                $("#lead").html("Achat de place <a href=\"#\" class=\"btn btn-primary\" id=\"btn-read-again\" >Lire un autre badge</a>")
                $("#btn-read-again").click(function() { vente() })
                table = '<table class="table" id="places-dispo">'+
                        '<thead>'+
                            '<th> Nom de l\'événement </th><th> Détails </th><th> Prix </th><th> Déjà acheté </th><th> Max </th><th> Acheter </th>'+
                        '</thead><tbody>'
                for(i=0; i<data.places.length; i++) {
                    tarifs[data.places[i].tarif_id] = data.places[i]
                    tarifs[data.places[i].tarif_id].nb_cart = 0
                    table += "<tr><td>" + data.places[i].event_name + "</td>\
                                  <td>" + data.places[i].tarif_name + "</td>\
                                  <td>" + data.places[i].price + " € </td>\
                                  <td>" + data.places[i].nb_buyed + "</td>\
                                  <td>" + data.places[i].nb_max + "</td>\
                                  <td><a href=\"#\" class=\"btn btn-acheter\" tarif_id=\""+ data.places[i].tarif_id + "\" > Acheter </a></td></tr>"
                }
                table += '</tbody></table>'
                $("#content").html(table)
                $(".btn-acheter").click(function () { var tarif_id = $(this).attr("tarif_id"); add_to_cart(tarif_id) })
                update_btn_acheter()
            }
        });   
    });
}

function run() {
    $("#lead").html("Verification de votre configuration en cours...")
    add_logout_button()
    check_badgeuse(vente)
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
            run()
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
