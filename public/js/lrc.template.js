var lrc = Vue.component('lrc' , {
	template:'<div><div style="width:200px;height:180px;background:url(\'./img/album_cover_player.png\');margin:0px auto;"><img id="singer_img" src="https:\/\/y.gtimg.cn\/music\/photo_new\/T002R300x300M000001fmWDH4I0aBK.jpg?max_age=2592000" style="width:180px;height:100%;" /></div><div ref="lrc_panel" id="lrc_panel" style="width:100%;border-radius:5px;overflow:hidden;margin-top:10px;" :style="{height:(height - 350)+\'px\'}"></div></div>' ,
	data:function(){
	  	return{
	  		width:width ,
	  		height:height ,
	  		music:Object
	  	} 
	  } ,
	  created:function(){
	  	var self = this ;
	  	this.$nextTick(function(){
			$("#lrc_panel").mCustomScrollbar({
				scrollButtons:{
					enable:true
				} ,
				autoHideScrollbar:true
			});
			this.width = document.offsetWidth
  			this.height = document.offsetHeight
			// 初始化歌词
			player.lrc_init(player.array[player.current["index"] - 1].lrc)
			$("#singer_img").attr("src" , player.array[player.current["index"] - 1].img)
	  	})
  	  } 
})
