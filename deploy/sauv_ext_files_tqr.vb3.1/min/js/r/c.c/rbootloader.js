function BootLoader(){var gt=this;var asdApps={searchbox:{isactive:false,name:"srhbx",lib:"SearchBox",lupd:null,xtras:{maininput:null},mods:{srhbox:{isactive:true,name:"srhbx",lib:"SearchBox",lupd:0,xtras:{},contents:[]}}},chatbox:{isactive:true,name:"chbx",lib:"ChatBox",xtras:{wsts:true,usts:true,ool:false,sbe:true,shr:false},mods:{convlist:{isactive:true,name:"convlist",lib:null,lupd:null,xtras:{maininput:""},contents:[]},convtheater:{isactive:false,name:"convtheater",lib:null,lupd:null,xtras:{maininput:"",
cvid:null,uid:null,upsd:null,ufn:null,uppic:null,tof:null},contents:[]}}}};this.OnLoad=function(){};this.OnBoot=function(){try{var f__=_f_ChkSsStorage();if(!f__)alert("Certaines fonctionnalit\u00e9s de Trenqr ne sont pas disponibles sur ce navigateur. Nous vous conseillons vivement d'utiliser un navigateur plus r\u00e9cent.");_f_Onload_AsdApps()}catch(ex){}};var _f_Onload_AsdApps=function(){if("asdApps"in sessionStorage);else sessionStorage.setItem("asdApps",JSON.stringify(asdApps))};var _f_ChkSsStorage=
function(){return typeof sessionStorage!=="undefined"}}try{var _RB=new BootLoader;(function(){_RB.OnBoot()})();window.onload=_RB.OnLoad}catch(ex){alert(ex)};
