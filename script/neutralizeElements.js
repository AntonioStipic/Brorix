var height = window.innerHeight;
var width = window.innerWidth;



var headerHeight = screen.height / 100 * 12 + "px";
document.getElementById("header").style.height = headerHeight;



var logoTop = height / 100 * 2 + "px";
var logoLeft = width / 100 * 8 + "px";
var logoWidth = width / 100 * 14 + "px";
document.getElementById("BrorixLogo").style.top = logoTop;
document.getElementById("BrorixLogo").style.left = logoLeft;
document.getElementById("BrorixLogo").style.width = logoWidth;



var titleLeft = width / 100 * 9 + "px";
var titleTop = height / 100 * -1.5 + "px";
document.getElementById("textTitle").style.left = titleLeft;
document.getElementById("textTitle").style.top = titleTop;
document.getElementById("textTitle").style.opacity = 0;



var headerLinksTop = height / 100 * 2 + "px";
document.getElementById("headerLinks").style.position = "fixed";
document.getElementById("headerLinks").style.top = headerLinksTop;



var homeLeft = width / 100 * 28 + "px";
var hotLeft = width / 100 * 40 + "px";
var aboutLeft = width / 100 * 55.3 + "px";

var headerLinksPadding = width / 100 * 4 + "px";
var headerLinksPaddingBottom = height / 100 * 2 + "px";
var headerLinksPaddingTop = height / 100 * 1 + "px";

document.getElementsByName("Home")[0].style.padding = headerLinksPadding;
document.getElementsByName("Home")[0].style.paddingBottom = headerLinksPaddingBottom;
document.getElementsByName("Home")[0].style.paddingTop = headerLinksPaddingTop;
document.getElementsByName("Home")[0].style.left = homeLeft;
document.getElementsByName("Home")[0].style.whiteSpace = "nowrap";
document.getElementsByName("Home")[0].style.overflow = "hidden";

document.getElementsByName("Hot")[0].style.padding = headerLinksPadding;
document.getElementsByName("Hot")[0].style.paddingBottom = headerLinksPaddingBottom;
document.getElementsByName("Hot")[0].style.paddingTop = headerLinksPaddingTop;
document.getElementsByName("Hot")[0].style.left = hotLeft;
document.getElementsByName("Hot")[0].style.whiteSpace = "nowrap";
document.getElementsByName("Hot")[0].style.overflow = "hidden";

document.getElementsByName("About")[0].style.padding = headerLinksPadding;
document.getElementsByName("About")[0].style.paddingBottom = headerLinksPaddingBottom;
document.getElementsByName("About")[0].style.paddingTop = headerLinksPaddingTop;
document.getElementsByName("About")[0].style.left = aboutLeft;
document.getElementsByName("About")[0].style.whiteSpace = "nowrap";
document.getElementsByName("About")[0].style.overflow = "hidden";



var videoURLwidth = document.getElementById("videoURL").offsetWidth;
var videoURLleft = (width - videoURLwidth) / 2 - 45;
document.getElementById("videoURL").style.left = videoURLleft + "px";

var buttonLeft = videoURLleft + videoURLwidth - 90 - 80;
document.getElementsByClassName("button")[0].style.left = buttonLeft + "px";

var notes02width = (width / 100) * 25;
notes02width = notes02width + "px";
document.getElementById("notes02").style.width = notes02width;