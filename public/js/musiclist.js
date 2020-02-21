
(function(win , doc){
	var MusicList = function(el , options){
	this.el = el
	this.options = options
	this.func = {}
	this.width = options.width == undefined ? this.el.offsetWidth : options.width
	//this.el.style.background = "url('./img/bg_empty1.png')"
	this.ul = document.createElement("ul")
	this.ul.setAttribute("class" , "music_list_panel")
	var self = this ;
	var div1 = document.createElement("div")
	div1.setAttribute("class" , "index")
	var div2 = document.createElement("div")
	div2.setAttribute("class" , "name")
	div2.innerHTML = "歌曲"
	var div3 = document.createElement("div")
	div3.setAttribute("class" , "singer")
	div3.innerHTML = "专辑"
	var div4 = document.createElement("div")
	div4.setAttribute("class" , "time")
	div4.innerHTML = "歌手"
	var li = document.createElement("li")
	li.setAttribute("class" , "music_list_item")
	li.appendChild(div1)
	li.appendChild(div2)
	li.appendChild(div3)
	li.appendChild(div4)
	this.ul.appendChild(li)
	this.ul.setAttribute("id" , "music_list")
	this.el.appendChild(this.ul)
	this.on = function(source , fun){
		if(this.func[source] == undefined){
			this.func[source] = []
		}
		this.func[source].push(fun)
	}
	$("#music_list").mCustomScrollbar({
		scrollButtons:{
			enable:true
		} ,
		callbacks:{
			onTotalScroll:function(obj){
				var d = self.func["scroll"]
				for(var i in d){
					d[i](obj)
				}
			}
		}
	});
	this.listClick = function(obj){
		var fs = this.func["click"]
		for(var i in fs){
			fs[i](obj) 
		}
	}
	this.add = function(data){
		var scroll = this.ul.querySelector(".mCSB_container")
		//1.计算当前列表中有多少个标签
		var nodes = scroll.childNodes
		var index = nodes.length
		for(var i in data){
			var div_index = document.createElement("div")
			div_index.setAttribute("class" , "index")
			var div_name = document.createElement("div")
			div_name.setAttribute("class" , "name")
			var div_singer = document.createElement("div")
			div_singer.setAttribute("class" , "singer")
			var div_time = document.createElement("div")
			div_time.setAttribute("class" , "time")
			div_index.innerHTML = index 
			div_name.innerHTML = data[i].name
			div_singer.innerHTML = data[i].album
			div_time.innerHTML = data[i].time
			var li = document.createElement("li")
			li.setAttribute("class" , "music_list_item")
			var obj = {"index":index , "row":{"name":data[i].name , "singer":data[i].singer , "time":data[i].time}}
			li.setAttribute("info" , JSON.stringify(obj))
			li.onclick = function(event){
				event = event ? event : window.event 
				var element = event.srcElement ? event.srcElement : event.target
				self.listClick(JSON.parse(element.parentNode.getAttribute("info")))
			}
			li.appendChild(div_index)
			li.appendChild(div_name)
			li.appendChild(div_singer)
			li.appendChild(div_time)
			scroll.appendChild(li)
			index = index + 1
		}
		$("#music_list").mCustomScrollbar("update");
	}
	this.clear = function(){
		var scroll = this.ul.querySelector(".mCSB_container")
		var d = scroll.childNodes 
		while(1){
			if(d.length != 1){
				scroll.removeChild(d[1])
			}else{
				break
			}
		}
	}
}
if (typeof module != "undefined" && module.exports) {
    module.exports = MusicList;
} else if ( typeof define == 'function' && define.amd ) {
    define( function () { return MusicList; } );
} else {
    window.MusicList = MusicList;
}
})(window , document)