var array_tank_ico = ["china-Ch01_Type59.png", "china-Ch02_Type62.png",
                      "china-Ch03_WZ-111.png", "china-Ch06_Renault_NC31.png"  ,
                      "france-ARL_44.png", "france-Bat_Chatillon155_58.png",
                      "france-AMX_AC_Mle1948.png"];

var ico_prefix = "./ICO/";


function reverseName(name){
    var str;
    str = name.substr(0,6) + 'REVERSE/' + name.substr(6);
    return str;
}


function moveTank(path_to_ico, point_right, point_left, tank_width, spend){
    $("#redsquare").attr("src",path_to_ico);
    $("#redsquare").animate({left:point_right}, spend);
    $("#redsquare").animate(
                            {left:'+=60',"width": (tank_width*0.05)}, "slow",
                            function(){
                                $("#redsquare").attr("class", "reverse");
                                $("#redsquare").attr("src", reverseName(path_to_ico));
                             }
    );
    $("#redsquare").animate( {left:'-=60',"width": tank_width},"slow");
    $("#redsquare").animate( {left: point_left}, spend);
    $("#redsquare").animate(
                            {left:'+=60',"width": (tank_width*0.05)},"slow",
                            function(){
                                $("#redsquare").attr("src", path_to_ico);
                                $("#redsquare").attr("class", "normal");
                                $("#redsquare").animate( {left:'-=60',"width": tank_width},"slow");
                             }
    )
}
