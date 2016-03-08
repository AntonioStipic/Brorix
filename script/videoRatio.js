var height = window.innerHeight;
var width = window.innerWidth;

videoWidth = (width / 100) * 60;
videoHeight = videoWidth / 16 * 9;
document.getElementById("videoBox").style.width = videoWidth + "px";
document.getElementById("videoBox").style.height = videoHeight + "px";
document.getElementsByClassName("center")[0].style.width = videoWidth + "px";