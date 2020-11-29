function liczCzas(ile) {
	godzin = Math.floor(ile / 3600);
	minut = Math.floor((ile - godzin * 3600) / 60);
	sekund = ile - minut * 60 - godzin * 3600;
	if (godzin < 10){ godzin = "0" + godzin; }
	if (minut < 10){ minut = "0" + minut; }
	if (sekund < 10){ sekund = "0" + sekund; }
	if (ile > 0){
		ile--;
		document.getElementById("zegar").innerHTML = godzin + ":" + minut + ":" + sekund;
		setTimeout("liczCzas("+ile+")", 1000);
	} else {
		document.getElementById("zegar").innerHTML = "Zakończono";
		location.href = "index.php?a=table";
	}
}

function postep(time, w_s, w_e) {
	time++;
	timming = Math.floor( (time - w_s) / (w_e - w_s) * 726);
	if (timming < 726) {
		document.getElementById("pasek").style.width = timming+"px";
		setTimeout("postep("+time+", "+w_s+", "+w_e+")", 1000);
	} else {
		document.getElementById("pasek").style.width = "726px";
	}
}