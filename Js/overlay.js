<button id="stopBtn">Stop</button>
<button id="startBtn">Start</button>


<h3>Counter</h3>
<p id="counter">0</p>

<h3>Every Five</h3>
<p id="fiver">0</p>








var cnt = 0;
var fivecnt = 0;
var go = false;

jQuery('#stopBtn').click(stopTimer);
jQuery('#startBtn').click(startTimer);

function timer() {
    if(!go)
        return;
    cnt++;
    if(cnt >= 5){
        cnt=0;
        everyFive();
    }
    jQuery("#counter").text(cnt);
    setTimeout(timer, 1000);
}

function everyFive(){
    fivecnt++;
    jQuery("#fiver").text(fivecnt);
}

function stopTimer(){
    go = false;  
} 
function startTimer(){
    go = true;
    timer();
    
}    