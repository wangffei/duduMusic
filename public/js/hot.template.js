var hot = Vue.component('hot', {
  template: '<div class="list_cover_panel"><div class="list_cover_panel_main" :style="{height:(height - 160) + \'px\'}"><div class="list_left"><div style="width:200px;height:180px;background:url(\'./img/album_cover_player.png\');margin:20px auto;"><img :src="img" style="width:180px;height:180px;" /></div><div style="width:180px;height:30px;line-height:30px;color:white;margin-left:65px;text-align:center;-ms-filter: \'progid:DXImageTransform.Microsoft.Shadow(Strength=24, Direction=0, Color=#CFCDC2)\';text-shadow: 0 0 24px #CFCDC2;filter: progid:DXImageTransform.Microsoft.Shadow(Strength=24, Direction=135, Color=#CFCDC2);">{{name}}</div></div><div ref="list_right" id="list_right" style="flex-grow:1;height:100%;"></div></div></div>' ,
  data:function(){
  	return{
  		width:width ,
  		height:height ,
  		img:"./img/player_cover.png" ,
  		name:"嘟嘟热榜" ,
  		list:[]
  	}
  } ,
  created:function(){
  	var self = this
  	
  	this.$nextTick(function(){
  		// 请求歌曲列表
	  	$.ajax({
	  		url:"/api/hot/" ,
	  		success:function(res){
	  			self.list = res.data
	  			var d = res.data 
	  			var arr = []
	  			for(var i in d){
	  				var obj = {}
	  				obj.name = d[i].songName
					obj.album = d[i].album
        			obj.time = d[i].singer
        			arr.push(obj)
	  			}
	  			var musicList = new MusicList(self.$refs["list_right"] , {})
	  			musicList.add(arr)
	  			musicList.on("click" , self.select)
	  		}
	  	})
	  	this.width = document.offsetWidth
  		this.height = document.offsetHeight
  	})
  },
  methods:{
  	select:function(obj){
  		var d = this.list[obj.index*1 - 1]
  		$.ajax({
  			url:"/api/music/"+d["id"] ,
  			success:function(res){
  				var data = {}
				data.id = res.data.id
				data.img = res.data.img
				data.lrc = res.data.lyric
				data.songName = res.data.songName
				data.singer = res.data.singer 
				data.album = res.data.album
				player.addAndPlay(data)
  			}
  		})
  	}
  }
})