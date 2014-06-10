// popup window function
function windowpop(url) {
    var width = 535;
    var height = 620;
    var leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
    var topPosition = (window.screen.height / 2) - ((height / 2) + 50);
    var ref = window.open(url, "Window2", "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,location=no,directories=no");
    return false;
}

// add dynamic css - method borrowed from http://taggedzi.com/articles/display/adding-css-to-a-page-using-javascript-without-jquery
function addCssClass ( selector, styles ){
    try {
        style = document.getElementById('custom_css_element');
        temp = style.innerHTML;
        style.innerHTML = temp + selector + "{ " + styles + "}\n";
    }
    catch (err) {
        style = document.createElement("style");
        style.id = 'custom_css_element'
        style.setAttribute('type', 'text/css');
        style.innerHTML = selector + "{ " + styles + " }\n";
        document.head.insertBefore(style,document.head.childNodes[0]);   
    }
}
