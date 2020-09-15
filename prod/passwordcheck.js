/*************************************************************************
 * 
 * Client Side - Basic Password Strength Check
 * 
**************************************************************************/


function scorePassword(pass) {
    var score = 0;
    if (!pass || pass ===''){
        return score;
    }
    
    if( pass.length < 8){
        return -1;
    }
    
    //var alpha1 = "abcdefghijklmnopqrstuvwxyzqwertyuiopasdfghjkl:zxcvbnm,./ABCDEFGHIJLMNOPQRSTUVWXYZ1234567890";
    //if( alpha1.match(pass)){
    //    return 0;
    //}

    // award every unique letter until 5 repetitions
    var letters = new Object();
    for (var i=0; i<pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
    }

    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += (variationCount - 1) * 10;

    return parseInt(score);
}

function checkPassStrength(pass) {
    var score = scorePassword(pass);
    if (score > 80){
    
        return "<span style='background-color:yellowgreen;color:black'>&nbsp;&nbsp;&nbsp;Password Strength - Strong&nbsp;&nbsp;&nbsp;</span>";
    }
    if (score > 60){
    
        return "<span style='background-color:yellow;color:black'>&nbsp;&nbsp;&nbsp;Password Strength - Good&nbsp;&nbsp;&nbsp;</span>";
    }
    if (score >= 30){
    
        return "<span style='background-color:red;color:white'>&nbsp;&nbsp;&nbsp;Password Strength - Weak&nbsp;&nbsp;&nbsp;</span>";
    }
    if (score >= 0 ){
    
        return "Not valid";
    }
    if (score < 0 ){
        return "Too Short - at least 8 Characters Required";
    }

    return "";
}