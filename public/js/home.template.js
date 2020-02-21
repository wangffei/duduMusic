var home = Vue.component('home', {
  template: '<div><div id="panel_top" :style="{height:(height - 80)/2+\'px\'}" ><div class="panel_top_contain" ><div style="width:500px;height:auto;font-size:15px;color:white;position:absolute;right:20px;">嘟嘟音乐欢迎您的到来，本平台为您整合各大音乐平台资源，让您可以仅仅通过我们的平台听到就能够享受各大音乐网站的音乐，并且我们提供一套简便而舒适的ui，让您可以充分放松自己</div><div class="panel_item" style="position:absolute;bottom:0px;width:128px;"><div @click="go(\'music\')" style="width:128px;height:64px;background:url(\'./img/music.png\') 0px 0px;cursor:pointer;background-size:100% 200%;"></div></div><div class="panel_item" style="position:absolute;bottom:0px;width:128px;" :style="{left:((width*0.8 - 512)/3+128+0.1*width)+\'px\'}"><div @click="go(\'album\')" style="width:128px;height:64px;background:url(\'./img/zhuanji.png\') 0px 0px;cursor:pointer;background-size:100% 200%;"></div></div><div class="panel_item" style="position:absolute;bottom:0px;width:128px;" :style="{left:(2*(width*0.8 - 512)/3+256+0.1*width)+\'px\'}"><div @click="go(\'hot\')" style="width:128px;height:64px;background:url(\'./img/hot.png\') 0px 0px;cursor:pointer;background-size:100% 200%;"></div></div><div class="panel_item" style="position:absolute;bottom:0px;width:128px;" :style="{left:(3*(width*0.8 - 512)/3+384+0.1*width)+\'px\'}"><div @click="go(\'search\')" style="width:128px;height:64px;background:url(\'./img/search.png\') 0px 0px;cursor:pointer;background-size:100% 200%;"></div></div></div></div><div id="panel_bottom" :style="{height:(height - 80)/2+\'px\'}" ><div class="panel_top_contain" ><div class="panel_item panel_item_bottom" style="position:absolute;top:0px;width:128px;"><div @click="go(\'music\')" style="width:128px;height:64px;background:url(\'./img/music.png\') 0px 64px;cursor:pointer;background-size:100% 200%;"></div><div @click="go(\'music\')" class="panel_item_title">我的音乐</div></div><div class="panel_item panel_item_bottom" style="position:absolute;top:0px;width:128px;" :style="{left:((width*0.8 - 512)/3+128+0.1*width)+\'px\'}"><div @click="go(\'album\')" style="width:128px;height:64px;background:url(\'./img/zhuanji.png\') 0px 64px;cursor:pointer;background-size:100% 200%;"></div><div @click="go(\'album\')" class="panel_item_title">热门榜单</div></div><div class="panel_item panel_item_bottom" style="position:absolute;top:0px;width:128px;" :style="{left:(2*(width*0.8 - 512)/3+256+0.1*width)+\'px\'}"><div @click="go(\'hot\')" style="width:128px;height:64px;background:url(\'./img/hot.png\') 0px 64px;cursor:pointer;background-size:100% 200%;"></div><div @click="go(\'hot\')" class="panel_item_title">热门歌曲</div></div><div class="panel_item panel_item_bottom" style="position:absolute;top:0px;width:128px;" :style="{left:(3*(width*0.8 - 512)/3+384+0.1*width)+\'px\'}"><div @click="go(\'search\')" style="width:128px;height:64px;background:url(\'./img/search.png\') 0px 64px;cursor:pointer;background-size:100% 200%;"></div><div @click="go(\'search\')" class="panel_item_title">搜索</div></div><div style="position:absolute;bottom:20px;text-align:center;color:white;width:80%;font-size:13px;">@copyright(c) 2019 嘟嘟音乐有限公司</div></div></div></div>' ,
  data:function(){
  	return{
  		width:width ,
  		height:height 
  	}
  } ,
  created:function(){
  	this.$nextTick(function(){
  		this.width = document.offsetWidth
  		this.height = document.offsetHeight
  	})
  } ,
  methods:{
  	go:function(msg){
  		$("#panel_top").css("bottom" , height/2+"px")
		$("#panel_bottom").css("top" , height/2+"px")
		var self = this ;
		window.setTimeout(function(){
			self.$router.push(msg)
		} , 200)
  	}
  }
})