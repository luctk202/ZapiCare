var Axios=axios.create({baseURL:window.location.origin,headers:{"X-Requested-With":"XMLHttpRequest","Content-Type":"application/json","X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),ProSell={config:{ajaxProcess:!1},loading:{show:function(){var e=$('<div  id="loading-overlay"></div>');$("body").append(e)},hide:function(){$("body").find("#loading-overlay").remove()}},readURL:function(e,i){if(e.files&&e.files[0]){let t=e.files.length,l=0;for($("#preview_"+i).html("");l<t;){let t=e.files[l],o=new FileReader;o.onload=function(e){$("#preview_"+i).append(ProSell.htmlFile(t,e))},o.readAsDataURL(t),l++}}},htmlFile:function(e,i){const t=e.type;return["image/gif","image/jpeg","image/png"].includes(t)?'<div style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative"><div style="width: 110px;height: 110px;padding:5px"><image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="" src="'+i.target.result+'"/></div><div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px"><div style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">'+e.name+'</div><small style="line-height: 20px">'+ProSell.formatBytes(e.size,0)+"</small></div></div>":'<div style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative"><div style="width: 110px;height: 110px;padding:5px;text-align: center;padding-top: 30px"><i class="fa fa-file-alt fa-3x"></i></div><div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px"><div style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">'+e.name+'</div><small style="line-height: 20px">'+ProSell.formatBytes(e.size,0)+"</small></div></div>"},formatBytes:function(e,i=2){if(!+e)return"0 Bytes";const t=i<0?0:i,l=Math.floor(Math.log(e)/Math.log(1024));return`${parseFloat((e/Math.pow(1024,l)).toFixed(t))} ${["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"][l]}`}};
