document.getElementById("loadmore").firstElementChild.onclick=async function(){
	//"yes, let's make doing GET requests as weird and convoluted as possible"
	let loadmore=document.getElementById("loadmore");
	let xhr = new XMLHttpRequest();
	let lastdate = loadmore.dataset.lastdate.split('.').reverse().join("-");
	xhr.open("GET", "/api?c=moresongs&startdate="+lastdate+"&max_amount=10&ashtml=1",true);
	xhr.onload=function(){
		if(xhr.status == 200){
			let grid=document.getElementById("tracks");
			let allanchors=this.responseXML.getElementsByTagName("a");
			loadmore.dataset.lastdate=allanchors[allanchors.length-1].children[3].textContent;
			for(let child of [...this.responseXML.body.children]){//WHY THE FUCK
				grid.appendChild(child);
				if(child.tagName=="BUTTON"){
					prepare_player(child.firstElementChild);
				}
			}
		}
	};
	xhr.responseType = "document";
	xhr.send();
};