/**
 * Created by gdahan on 02/10/2015.
 */
$(document).ready(function(){

    $('#myModal').modal({ show : false})

    var list_package = [];

    $(".dir-item").each(function(){
        list_package.push( $(this).attr("data-package"));
    });

    if(id_package != "")
    {
        if(list_package.indexOf(id_package) != -1){
            clickItem($("[data-package='"+id_package+"']"));
            $(".file-detail").show();
        } else {
            console.log("ok");
            $('#myModal').modal('show');
        }
    }

    $('.scroll-pane').each(
        function()
        {
            $(this).jScrollPane(
                {
                    showArrows: $(this).is('.arrow')
                }
            );
            var api = $(this).data('jsp');
            var throttleTimeout;
            $(window).bind(
                'resize',
                function()
                {
                    if (!throttleTimeout) {
                        throttleTimeout = setTimeout(
                            function()
                            {
                                api.reinitialise();
                                throttleTimeout = null;
                            },
                            50
                        );
                    }
                }
            );
        }
    )

    $(".dir-item").click(function(){
        $(".file-detail").show("slow")
        if($(".active").length) {
            $(".file-detail ul").fadeOut("slow");
        }
        else {
            $(".file-detail ul").hide();
        }

        clickItem($(this));
        history.pushState({}, "", window.location.pathname + '?package=' + $(this).attr("data-package"));
        $(".scroll-pane").data('jsp').reinitialise();

    });

    $( document ).keydown (function( event ) {
        if (event.which == 27 || event.keyCode == 27) {
            event.preventDefault();
            $(".file-detail").hide()
            $(".file-manager").removeClass("col-md-8");
            $(".file-manager").addClass("col-md-12");
            $(".scroll-pane").data('jsp').reinitialise();
            $(".active").removeClass("active");
        }
    });
});

function clickItem(selector){
    $(".file-manager").removeClass("col-md-12");
    $(".file-manager").addClass("col-md-8");
    $(".active").removeClass("active");
    selector.addClass("active");

    $.ajax({
        url : "package.php",
        method : "GET",
        dataType : "json",
        data : { package : selector.attr("data-package")},
        beforeSend : function(){
            $(".file-detail").html('<img class="ajax-loader" src="image/ajax-loader.gif">')
        },
        success : function(msg) {
            if(msg.error == "")
            {
                displayDetail(msg);
            } else {
                alert(msg.error);
            }
        }
    })
}

function displayDetail(msg)
{
    var li_package ='<li class="detail-pdl"><i class="glyphicon glyphicon-link"></i> <a href="'+document.location.origin+msg["download link"]+'">T\351l\351charger le package</a></li>';
    var li_release ='<li class="detail-rndl"><i class="glyphicon glyphicon-link"></i> <a href="'+document.location.origin+msg["release link"]+'">T\351l\351charger la release note</a></li>';

    if(msg["download link"] == "")
    {
        li_package = '<li class="detail-pdl"><i class="glyphicon glyphicon-alert" style="color: red ; font-size: 24px; padding-right: 5px"></i> Package manquant</li>';
    }

    if(msg["release link"] == "")
    {
        li_release = '<li class="detail-rndl"><i class="glyphicon glyphicon-alert" style="color: red ; font-size: 24px; padding-right: 5px"></i> Release note manquante</li>';
    }

    $(".file-detail").html(
        '<ul class="detail-list" style="display: none">'+
            '<li class="detail-icon"><img src="image/Icon-package.png"> </li>'+
            '<li class="detail-version"><b>Version :</b> '+msg["version"]+'</li>'+
            '<li class="detail-md5"><b>MD5sum : </b>'+msg["MD5checksum"]+'</li>'+
            '<li class="datail-date"><b>Date : </b>'+msg["date"]+'</li>'+
            li_package+
            li_release+
        '</ul>');

    $(".file-detail ul").fadeIn("slow");
}