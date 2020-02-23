var music = Vue.component('music', {
  template: '<div id="music_main_panel"><div id="music_main" class="row" style="width:100%;height:100%;display:flex;" :style="{height:(height - 160)+\'px\'}"><div ref="music_main_left" class="music_main_left" :style="{height:(height - 160)+\'px\'}"></div><div ref="music_main_right" class="music_main_right" style="flex-grow:1;height:100%;"><div style="width:200px;float:right;height:100%;display:flex;flex-direction:column;"><div style="width:200px;height:180px;background:url(\'./img/album_cover_player.png\');"><img id="singer_img" src="https:\/\/y.gtimg.cn\/music\/photo_new\/T002R300x300M000001fmWDH4I0aBK.jpg?max_age=2592000" style="width:180px;height:100%;" /></div><div ref="lrc_panel" id="lrc_panel" style="width:100%;border-radius:5px;overflow:hidden;margin-top:10px;" :style="{height:(height - 350)+\'px\'}"></div></div></div></div><div id="music_bottom" ><div style="width:85%;"></div></div></div>' ,
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
  		self.music = new MusicList(this.$refs.music_main_left , {}) ;
		$("#lrc_panel").mCustomScrollbar({
			scrollButtons:{
				enable:true
			} ,
			autoHideScrollbar:true
		});
		//console.log(this.item_click)
		var arr = []
		var d = player.array 
		for(var i in d){
			var obj = {}
			obj.name = d[i].songName
			obj.album = d[i].album
			obj.time = d[i].singer
			arr.push(obj)
		}
		self.music.add(arr)
		self.music.on("click" , this.item_click)
		// 初始化歌词
		player.lrc_init(player.array[player.current["index"] - 1].lrc)
		$("#singer_img").attr("src" , player.array[player.current["index"] - 1].img)
		this.width = document.offsetWidth
  		this.height = document.offsetHeight
  	})
	  } ,
	  methods:{
	  	item_click:function(obj){
	  		player.play(obj.index*1)
	  	}
	  }
})