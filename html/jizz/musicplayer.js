const playbtn_play = "M25 90 49 74l0-48L25 10zM49 74 85 50l0 0L49 26z";
const playbtn_pause= "M25 85l22.5 0 0-70L25 15zM62.5 85 85 85 85 15 62.5 15z";
const playbtn_stop = "M25 80l30 0 0-60-30 0zm30 0 30 0 0-60-30 0z";

var curplayer = null;
var state = 0;//0→stopped, 1→paused, 2→playing

function secs_as_mins(secs){
	//I HATE JAVASCRIPT
	//'easy language' MY ASS
	//do you know how EVERY fucking programming language has format strings?
	//javascript does too, but YOU CAN'T CHANGE THE FORMATTING
	//THEY'RE JUST GLORIFIED STRING CONCATS!
	//also there's a timedate formatter, WHICH DOESN'T LET YOU FORMAT DURATIONS, ONLY DATES
	return Math.floor(secs/60)+':'+(''+Math.round(secs%60)).padStart(2,'0');
}

function setProgbarWidth(bar,perc,bufrd){//this got more complicated than I anticipated
	let bgi = "linear-gradient(to right,var(--acc_h) 0,var(--acc_h) "+perc+"%";
	let lastperc = perc;
	if(bufrd!=null){
		for(let range of bufrd){
			bgi+=",#0000 "+lastperc+"%,#0000 "+range[0]+"%,var(--acc) "+range[0]+"%,var(--acc) "+range[1]+"%";
			lastperc=range[1];
		}
	}
	bar.children[0].style.backgroundImage=bgi+",#0000 "+lastperc+"%)";
}
function stopPlayer(player){
	player.data_player.pause();
	player.data_player.currentTime=0;
	player.data_svg.setAttribute("d",playbtn_play);
	master_player.setAttribute("disabled","");
	master_player.data_svg.setAttribute("d",playbtn_play);
	player.data_player.ontimeupdate=null;
	setProgbarWidth(master_player.data_prog,0,null);
	master_player.data_progtext.textContent="0:00 / 0:00";
	state=0;
	curplayer=null;
}
function startPlayer(player){
	curplayer = player;
	state=2;
	player.data_player.ontimeupdate=ontimeupdate_player;
	setPlayerVol(master_player.data_curVol)
	try{
		player.data_player.play();
	}catch(err){
		console.log(err);
	}
	player.data_svg.setAttribute("d",playbtn_stop);
	master_player.removeAttribute("disabled");
	master_player.data_svg.setAttribute("d",playbtn_pause);
}
function pausePlayer(player){
	player.data_player.pause();
	master_player.data_svg.setAttribute("d",playbtn_play);
	player.data_svg.setAttribute("d",playbtn_play);
	state=1;
}
function unpausePlayer(player){
	player.data_player.play();
	master_player.data_svg.setAttribute("d",playbtn_pause);
	player.data_svg.setAttribute("d",playbtn_stop);
	state=2;
}
function setPlayerPos(peru){
	curplayer.data_player.currentTime=peru*curplayer.data_player.duration;
}
function setPlayerVol(peru){
	master_player.data_curVol=peru;
	curplayer.data_player.volume=peru;
	setProgbarWidth(master_player.data_vol,peru*100,[[0,100]]);
	master_player.data_voltext.textContent=(Math.round(peru*100)+"%").padStart(4,' ');
}

function ontimeupdate_player(event){
	let bufrd=[];
	for(let i=0;i<this.buffered.length;i++){
		if(this.buffered.end(i)>this.currentTime){
			bufrd.push([100*this.buffered.start(i)/this.duration,100*this.buffered.end(i)/this.duration]);
		}
	}
	setProgbarWidth(masterplayer.data_prog,100*this.currentTime/this.duration,bufrd);
	master_player.data_progtext.textContent=secs_as_mins(this.currentTime)+" / "+secs_as_mins(this.duration);
	if((this.currentTime/this.duration)==1){
		stopPlayer(curplayer);
	}
}
function onclick_player(event){
	if(state==0){
		startPlayer(this);
	}else if(state==1){
		if(curplayer!=this){
			stopPlayer(curplayer);
		}
		startPlayer(this);
	}else if(state==2){
		if(curplayer==this){
			stopPlayer(curplayer);
		}else{
			stopPlayer(curplayer);
			startPlayer(this);
		}
	}
}
function onclick_masterprog(event){
	if(!master_player.hasAttribute("disabled")){
		setPlayerPos((event.layerX-this.firstElementChild.offsetLeft)/this.firstElementChild.offsetWidth)
	}
}
function ondrag_masterprog(event){
	if(!master_player.hasAttribute("disabled")){
		if(event.buttons & 1){//if primary mouse key is held down
			setPlayerPos((event.layerX-this.firstElementChild.offsetLeft)/this.firstElementChild.offsetWidth);
		}
	}
}
function onclick_mastervol(event){
	if(!master_player.hasAttribute("disabled")){
		setPlayerVol((event.layerX-this.firstElementChild.offsetLeft)/this.firstElementChild.offsetWidth)
	}
}
function ondrag_mastervol(event){
	if(!master_player.hasAttribute("disabled")){
		if(event.buttons & 1){//if primary mouse key is held down
			setPlayerVol((event.layerX-this.firstElementChild.offsetLeft)/this.firstElementChild.offsetWidth);
		}
	}
}

window.addEventListener("load",function(){
	window.master_player = document.getElementById("masterplayer");
	master_player.data_svg=master_player.getElementsByTagName("path")[0];
	master_player.data_prog=document.getElementById("audioprog");
	master_player.data_vol=document.getElementById("audiovol");
	master_player.data_progtext=master_player.data_prog.nextElementSibling;
	master_player.data_voltext=master_player.data_vol.nextElementSibling;
	master_player.data_curVol=1;
	master_player.data_svg.parentElement.onclick=(event) => {
		if(!master_player.hasAttribute("disabled")){
			if(state==1){
				unpausePlayer(curplayer);
			}else if(state==2){
				pausePlayer(curplayer);
			}
		}
	}
	master_player.data_prog.onclick=onclick_masterprog;
	master_player.data_prog.onmousemove=ondrag_masterprog;
	master_player.data_vol.onclick=onclick_mastervol;
	master_player.data_vol.onmousemove=ondrag_mastervol;
});