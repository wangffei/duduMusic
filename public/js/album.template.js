var album = Vue.component('album', {
  template: '<div class="list_cover_panel"><div id="albumList" :style="{height:(height - 160) + \'px\'}"><div style="width:100%;font-size:18px;color:white;text-align:center;height:40px;line-height:40px;">热门榜单</div></div></div>' ,
  data:function(){
  	return{
  		width:width ,
  		height:height 
  	}
  } ,
  created:function(){
  	var self = this ;
  	this.$nextTick(function(){
  		$.ajax({
	  		url:"/api/albums" ,
	  		success:function(res){
	  			var d = res.data
	  			var div = $("<div style='display:flex;width:100%;flex-wrap:wrap;'></div>")
	  			for(var i in d){
	  				var temp = $("<div class='hot_album_item' onclick='album_select('"+d[i].id+"')' albuminfo='{\"id\":"+d[i].id+" , \"img\":\""+d[i].img+"\" , \"name\":\""+d[i].name+"\"}'><img class='album_item_img' src='"+d[i].img+"' /><div style='width:100%;color:white;text-align:center;height:20px;line-height:20px;'>"+d[i].name+"</div></div>") ;
	  				div.append(temp)
	  				temp.click(function(){
	  					var info = JSON.parse($(this).attr("albuminfo"))
	  					self.$router.push("list?id="+info.id+"&img="+info.img+"&name="+info.name)
	  				})
	  				// if(i*1 != 0 && (i*1+1) % 5 == 0){
	  				// 	$("#albumList").append(div)
	  				// 	div = $("<div style='display:flex;width:100%;'></div>")
	  				// }
	  			}
	  			$("#albumList").append(div)
	  			$("#albumList").mCustomScrollbar({
					scrollButtons:{
						enable:true
					} ,
					scrollInertia:1000
				});
	  		}
	  	})
	  	this.width = document.offsetWidth
  		this.height = document.offsetHeight
  	})
  } ,
  methods:{
  }
})