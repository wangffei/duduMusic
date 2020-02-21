var search = Vue.component('search', {
  template: '<div class="list_cover_panel" ><div class="list_cover_panel_main" :style="{height:(height - 160) + \'px\'}"><div class="list_left"><div class="search_panel_img"><img :src="img" style="width:180px;height:180px;" /></div><div class="search_panel_title" >{{name}}</div></div><div id="list_right" style="flex-grow:1;height:100%;display:flex;flex-direction:column;"><div id="search" style="width:100%;height:80px;overflow:hidden;border-bottom:0.2px solid #353333;"><div class="search_input_panel"><div id="search_input_parent"><input id="search_input" v-model="key" type="text" placeholder="请输入检索词" /></div><div @click="search()" id="search_input_btn">搜索</div></div></div><div id="search_panel" ref="search_panel" style="width:100%;" :style="{height:(height - 160 - 80) + \'px\'}"></div></div></div></div>' ,
  data:function(){
  	return{
  		width:width ,
  		height:height ,
  		img:"./img/player_cover.png" ,
  		name:"嘟嘟搜索" ,
  		list:[] ,
  		key:"" ,
  		scroll:Object ,
  		page:1
  	}
  } ,
  created:function(){
  	var self = this
  	this.$nextTick(function(){
  		self.scroll = new MusicList(this.$refs["search_panel"] , {})
  		self.scroll.on("click" , self.select)
  		self.scroll.on("scroll" , self.onScroll)
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
				data.img = res.data.img
				data.lrc = res.data.lyric
				data.songName = res.data.songName
				data.singer = res.data.singer 
				data.album = res.data.album
				data.id = res.data.id
				player.addAndPlay(data)
  			}
  		})
  	} ,
  	onScroll:function(obj){
  		this.page = this.page + 1
  		var self = this 
  		$.ajax({
  			url:"/api/search/"+self.key+"/"+self.page ,
  			success:function(res){
  				self.page = 1 
  				self.list = self.list.concat(res.data)
  				var d = res.data 
	  			var arr = []
	  			for(var i in d){
	  				var obj = {}
	  				obj.name = d[i].name
					obj.album = d[i].album
        			obj.time = d[i].artist[0]
        			arr.push(obj)
	  			}
	  			self.scroll.add(arr)
  			}
  		})
  	},
  	search:function(){
  		var self = this 
  		this.scroll.clear()
  		$.ajax({
  			url:"/api/search/"+self.key+"/"+1 ,
  			success:function(res){
  				self.page = 1 
  				self.list = res.data
  				var d = res.data 
	  			var arr = []
	  			for(var i in d){
	  				var obj = {}
	  				obj.name = d[i].name
					obj.album = d[i].album
        			obj.time = d[i].artist[0]
        			arr.push(obj)
	  			}
	  			self.scroll.add(arr)
  			}
  		})
  	}
  }
})