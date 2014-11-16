//Dynamic countup Script- © Dynamic Drive (www.dynamicdrive.com)
//For full source code, 100's more DHTML scripts, and TOS,
//visit http://www.dynamicdrive.com

function setcountup(theyear,themonth,theday,thehour,themin,thesec){
yr=theyear;
mo=themonth;
da=theday
hr=thehour
mn=themin
sc=thesec
}

//////////CONFIGURE THE countup SCRIPT HERE//////////////////

//STEP 1: Configure the date to count up from, in the format year, month, day:
//This date should be less than today
// year, mth, day, hr, ms, sec
//setcountup(2006,10,16, 1, 39, 00)

//STEP 2: Configure text to be attached to count up
var displaymessage=""

//STEP 3: Configure the below 5 variables to set the width, height, background color, and text style of the countup area
var countupwidth='10%'
var countupheight='20px' //applicable only in NS4
var countupbgcolor=''
var opentags=''
var closetags=''

//////////DO NOT EDIT PASS THIS LINE//////////////////

var montharray=new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")
var crosscount=''

function start_countup(){
if (document.layers)
document.countupnsmain.visibility="show"
else if (document.all||document.getElementById)
crosscount=document.getElementById&&!document.all?document.getElementById("countupie") : countupie
countup()
}

if (document.all||document.getElementById)
document.write('<span id="countupie" style="width:'+countupwidth+'; background-color:'+countupbgcolor+'"></span>')

window.onload=start_countup

function PadDigits(n, totalDigits) 
{ 
        n = n.toString(); 
        var pd = ''; 
        if (totalDigits > n.length) 
        { 
            for (i=0; i < (totalDigits-n.length); i++) 
            { 
                pd += '0'; 
            } 
        } 
        return pd + n.toString(); 
} 

function countup(){
var newDate = new Date();
newDate.setTime(newDate.getTime() + dateOffset);
var today=newDate;
var todayy=today.getYear()
if (todayy < 1000)
todayy+=1900
var todaym=today.getMonth()
var todayd=today.getDate()
var todayh=today.getHours()
var todaymin=today.getMinutes()
var todaysec=today.getSeconds()
var todaystring=montharray[todaym]+" "+todayd+", "+todayy+" "+todayh+":"+todaymin+":"+todaysec
paststring=montharray[mo-1]+" "+da+", "+yr+" "+hr+":"+mn+":"+sc
dd=Date.parse(todaystring)-Date.parse(paststring)
dday=Math.floor(dd/(60*60*1000*24)*1)
dhour=Math.floor((dd%(60*60*1000*24))/(60*60*1000)*1)
dmin=Math.floor(((dd%(60*60*1000*24))%(60*60*1000))/(60*1000)*1)
dmin=PadDigits(dmin,2);
dsec=Math.floor((((dd%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1)
dsec=PadDigits(dsec,2);
dhours=(dday*24)+dhour
dhours=PadDigits(dhours,2);

if (document.layers){
document.countupnsmain.document.countupnssub.document.write(opentags+dday+ " days, "+dhour+" hours, "+dmin+" minutes, and "+dsec+" seconds "+displaymessage+closetags)
document.countupnsmain.document.countupnssub.document.close()
}
else if (document.all||document.getElementById)
crosscount.innerHTML=opentags+dhours+"-"+dmin+"-"+dsec+" "+displaymessage+closetags

setTimeout("countup()",1000)
}
