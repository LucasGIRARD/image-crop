var angle = 0;
var Image
var unit = 1;
var heightImage
var widthImage

var newHeightImage
var newWidthImage

var blackTop
var blackBottom
var blackLeft
var blackRight

var blackTopHeight
var blackBottomHeight
var blackLeftWidth
var blackRightWidth

function load(){
    Image = document.getElementById('photo');
    heightImage = parseInt(Image.height, 10);
    widthImage = parseInt(Image.width, 10);


    document.getElementById('body').style.width = widthImage+"px";

    blackTop = document.getElementById('blackTop').style;
    blackBottom = document.getElementById('blackBottom').style;
    blackLeft = document.getElementById('blackLeft').style;
    blackRight = document.getElementById('blackRight').style;

    blackTop.width = widthImage+"px";

    blackBottom.top = heightImage+"px";
    blackBottom.width = widthImage+"px";

    blackLeft.height = heightImage+2+"px";

    blackRight.height = heightImage+2+"px";
    blackRight.left = widthImage+"px";


    blackLeftWidth = parseInt(blackLeft.width, 10)-1;
    blackRightWidth = parseInt(blackRight.width, 10)-1;
    blackTopHeight = parseInt(blackTop.height, 10)-1;
    blackBottomHeight = parseInt(blackBottom.height, 10)-1;


    heightValue();
    widthValue();
    angleValue();
}
function changeUnit() {
    unit = parseInt(document.getElementById('unit').value, 10);
}

function heightValue() {
    newHeightImage = heightImage - blackTopHeight - blackBottomHeight;
    document.getElementById('heightValue').innerHTML = newHeightImage;
}
function widthValue() {
    newWidthImage = widthImage - blackLeftWidth - blackRightWidth;
    document.getElementById('widthValue').innerHTML = newWidthImage;
}
function angleValue() {
    document.getElementById('angleValue').innerHTML = angle;
}

function heightTopMore() {
    if (blackTopHeight >= 0){
        blackTop.height = parseInt(blackTop.height, 10)+unit +"px";
        blackTopHeight = parseInt(blackTop.height, 10)-1;
    }
    else if(blackTopHeight < 0){
        blackTop.top = parseInt(blackTop.top, 10)+unit +"px";
        blackLeft.top = parseInt(blackLeft.top, 10)+unit +"px";
        blackLeft.height = parseInt(blackLeft.height, 10)-unit +"px";
        blackRight.top = parseInt(blackRight.top, 10)+unit +"px";
        blackRight.height = parseInt(blackRight.height, 10)-unit +"px";
        blackTopHeight = parseInt(blackTop.height, 10)-1+parseInt(blackTop.top, 10)+1;
    }
    heightValue();
}

function heightTopLess() {
    if (blackTopHeight > 0){
        blackTop.height = parseInt(blackTop.height, 10)-unit +"px";
        blackTopHeight = parseInt(blackTop.height, 10)-1;
    }
    else if(blackTopHeight <= 0){
        blackTop.top = parseInt(blackTop.top, 10)-unit +"px";
        blackLeft.top = parseInt(blackLeft.top, 10)-unit +"px";
        blackLeft.height = parseInt(blackLeft.height, 10)+unit +"px";
        blackRight.top = parseInt(blackRight.top, 10)-unit +"px";
        blackRight.height = parseInt(blackRight.height, 10)+unit +"px";
        blackTopHeight = parseInt(blackTop.height, 10)-1+parseInt(blackTop.top, 10)+1;
    }
    heightValue();
}

function heightBottomMore() {
    if (blackBottomHeight >= 0){
        blackBottom.height = parseInt(blackBottom.height, 10)+unit +"px";
        blackBottom.top = parseInt(blackBottom.top, 10)-unit +"px";
        blackBottomHeight = parseInt(blackBottom.height, 10)-1;
    }
    else if(blackBottomHeight < 0){
        blackBottom.top = parseInt(blackBottom.top, 10)-unit +"px";
        blackLeft.height = parseInt(blackLeft.height, 10)-unit +"px";
        blackRight.height = parseInt(blackRight.height, 10)-unit +"px";
        blackBottomHeight = parseInt(blackBottom.height, 10)-1-(parseInt(blackBottom.top, 10) - heightImage);
    }
    heightValue();
}

function heightBottomLess() {
    if (blackBottomHeight > 0){
        blackBottom.height = parseInt(blackBottom.height, 10)-unit +"px";
        blackBottom.top = parseInt(blackBottom.top, 10)+unit +"px";
        blackBottomHeight = parseInt(blackBottom.height, 10)-1;
    }
    else if(blackBottomHeight <= 0){
        blackBottom.top = parseInt(blackBottom.top, 10)+unit +"px";
        blackLeft.height = parseInt(blackLeft.height, 10)+unit +"px";
        blackRight.height = parseInt(blackRight.height, 10)+unit +"px";
        blackBottomHeight = parseInt(blackBottom.height, 10)-1-(parseInt(blackBottom.top, 10) - heightImage);
    }
    heightValue();
}

function widthLeftMore() {
    if (blackLeftWidth >= 0){
        blackLeft.width = parseInt(blackLeft.width, 10)+unit +"px";
        blackLeftWidth = parseInt(blackLeft.width, 10)-1;
    }
    else if(blackLeftWidth < 0){
        blackLeft.left = parseInt(blackLeft.left, 10)+unit +"px";
        blackTop.left = parseInt(blackTop.left, 10)+unit +"px";
        blackTop.width = parseInt(blackTop.width, 10)-unit +"px";
        blackBottom.left = parseInt(blackBottom.left, 10)+unit +"px";
        blackBottom.width = parseInt(blackBottom.width, 10)-unit +"px";
        blackLeftWidth = (parseInt(blackLeft.width, 10)-1)+(parseInt(blackLeft.left, 10)+1);
    }
    widthValue();
}

function widthLeftLess() {
    if (blackLeftWidth > 0){
        blackLeft.width = parseInt(blackLeft.width, 10)-unit +"px";
        blackLeftWidth = parseInt(blackLeft.width, 10)-1;
    }
    else if(blackLeftWidth <= 0){
        blackLeft.left = parseInt(blackLeft.left, 10)-unit +"px";
        blackTop.left = parseInt(blackTop.left, 10)-unit +"px";
        blackTop.width = parseInt(blackTop.width, 10)+unit +"px";
        blackBottom.left = parseInt(blackBottom.left, 10)-unit +"px";
        blackBottom.width = parseInt(blackBottom.width, 10)+unit +"px";
        blackLeftWidth = parseInt(blackLeft.width, 10)-1+parseInt(blackLeft.left, 10)+1;
    }
    widthValue();
}

function widthRightMore() {
    if (blackRightWidth >= 0){
        blackRight.width = parseInt(blackRight.width, 10)+unit +"px";
        blackRight.left = parseInt(blackRight.left, 10)-unit +"px";
        blackRightWidth = parseInt(blackRight.width, 10)-1;
    }
    else if(blackRightWidth < 0){
        blackRight.left = parseInt(blackRight.left, 10)-unit +"px";
        blackTop.width = parseInt(blackTop.width, 10)-unit +"px";
        blackBottom.width = parseInt(blackBottom.width, 10)-unit +"px";
        blackRightWidth = parseInt(blackRight.width, 10)-1-(parseInt(blackRight.left, 10) - widthImage);
    }
    widthValue();
}

function widthRightLess() {
    if (blackRightWidth > 0){
        blackRight.width = parseInt(blackRight.width, 10)-unit +"px";
        blackRight.left = parseInt(blackRight.left, 10)+unit +"px";
        blackRightWidth = parseInt(blackRight.width, 10)-1;
    }
    else if(blackRightWidth <= 0){
        blackRight.left = parseInt(blackRight.left, 10)+unit +"px";
        blackTop.width = parseInt(blackTop.width, 10)+unit +"px";
        blackBottom.width = parseInt(blackBottom.width, 10)+unit +"px";
        blackRightWidth = parseInt(blackRight.width, 10)-1-(parseInt(blackRight.left, 10) - widthImage);
    }
    widthValue();
}

function turnMore() {
    angle = angle + unit ;
    $('#photo').rotate(angle);
    angleValue();
}
function turnLess() {
    angle = angle - unit ;
    $('#photo').rotate(angle);
    angleValue();
}

function loadingValues() {
    document.hidden.imageNewWidth.value = newWidthImage;
    document.hidden.imageNewHeight.value = newHeightImage;
    document.hidden.blackTop.value = blackTopHeight;
    document.hidden.blackBottom.value = blackBottomHeight;
    document.hidden.blackRight.value = blackRightWidth;
    document.hidden.blackLeft.value = blackLeftWidth;
    document.hidden.angle.value = -angle;
    document.hidden.photo.value = photo;
}