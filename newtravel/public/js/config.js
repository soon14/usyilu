var Config={setDivAttr:function(){var t=$(window).width()-120;$(".content-rig")[0].style.width=t+"px";var a=$(window).height();$(".menu-left")[0].style.height=a+"px"},getConfig:function(t,a,e){var n="object"==typeof e?e.join(","):"";n="string"==typeof e?e:n;var i=SITEURL+"config/ajax_getconfig";$.ajax({type:"POST",url:i,dataType:"json",data:{webid:t,fields:n},success:function(t){a(t)}})},saveConfig:function(t,a){var e=SITEURL+"config/ajax_saveconfig",n=$("#configfrm").serialize(),n=n+"&webid="+t;$.ajax({type:"POST",url:e,dataType:"json",data:n,success:function(t){1==t.status&&(ST.Util.showMsg("保存成功",4),$.post(SITEURL+"index/ajax_clearcache"),a&&a())}})},getWaterConfig:function(t){var a=SITEURL+"config/ajax_getwaterconfig";$.ajax({type:"POST",url:a,dataType:"json",success:function(a){t(a)}})}};