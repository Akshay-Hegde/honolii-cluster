(function(l){typeof define==="function"&&define.amd?define(l):window.purl=l()})(function(){function l(b,c){for(var a=decodeURI(b),a=p[c?"strict":"loose"].exec(a),d={attr:{},param:{},seg:{}},g=14;g--;)d.attr[q[g]]=a[g]||"";d.param.query=m(d.attr.query);d.param.fragment=m(d.attr.fragment);d.seg.path=d.attr.path.replace(/^\/+|\/+$/g,"").split("/");d.seg.fragment=d.attr.fragment.replace(/^\/+|\/+$/g,"").split("/");d.attr.base=d.attr.host?(d.attr.protocol?d.attr.protocol+"://"+d.attr.host:d.attr.host)+
(d.attr.port?":"+d.attr.port:""):"";return d}function r(b){b=b.tagName;return typeof b!=="undefined"?s[b.toLowerCase()]:b}function n(b,c,a,d){var g=b.shift();if(g){var e=c[a]=c[a]||[];if("]"==g)if(k(e))""!==d&&e.push(d);else if("object"==typeof e){var c=b=e,a=[],f;for(f in c)c.hasOwnProperty(f)&&a.push(f);b[a.length]=d}else c[a]=[c[a],d];else{~g.indexOf("]")&&(g=g.substr(0,g.length-1));if(!o.test(g)&&k(e))if(c[a].length===0)e=c[a]={};else{f={};for(var j in c[a])f[j]=c[a][j];e=c[a]=f}n(b,e,g,d)}}else k(c[a])?
c[a].push(d):c[a]="object"==typeof c[a]?d:"undefined"==typeof c[a]?d:[c[a],d]}function m(b){return t(String(b).split(/&|;/),function(c,a){try{a=decodeURIComponent(a.replace(/\+/g," "))}catch(d){}var b=a.indexOf("="),e;a:{for(var f=a.length,j,h=0;h<f;++h)if(j=a[h],"]"==j&&(e=!1),"["==j&&(e=!0),"="==j&&!e){e=h;break a}e=void 0}f=a.substr(0,e||b);e=a.substr(e||b,a.length);e=e.substr(e.indexOf("=")+1,e.length);f===""&&(f=a,e="");b=f;f=e;if(~b.indexOf("]")){var i=b.split("[");n(i,c,"base",f)}else{if(!o.test(b)&&
k(c.base)){e={};for(i in c.base)e[i]=c.base[i];c.base=e}if(b!=="")i=c.base,e=i[b],typeof e==="undefined"?i[b]=f:k(e)?e.push(f):i[b]=[e,f]}return c},{base:{}}).base}function t(b,c,a){for(var d=0,g=b.length>>0;d<g;)d in b&&(a=c.call(void 0,a,b[d],d,b)),++d;return a}function k(b){return Object.prototype.toString.call(b)==="[object Array]"}function h(b,c){arguments.length===1&&b===!0&&(c=!0,b=void 0);b=b||window.location.toString();return{data:l(b,c||!1),attr:function(a){a=u[a]||a;return typeof a!=="undefined"?
this.data.attr[a]:this.data.attr},param:function(a){return typeof a!=="undefined"?this.data.param.query[a]:this.data.param.query},fparam:function(a){return typeof a!=="undefined"?this.data.param.fragment[a]:this.data.param.fragment},segment:function(a){return typeof a==="undefined"?this.data.seg.path:(a=a<0?this.data.seg.path.length+a:a-1,this.data.seg.path[a])},fsegment:function(a){return typeof a==="undefined"?this.data.seg.fragment:(a=a<0?this.data.seg.fragment.length+a:a-1,this.data.seg.fragment[a])}}}
var s={a:"href",img:"src",form:"action",base:"href",script:"src",iframe:"src",link:"href"},q="source,protocol,authority,userInfo,user,password,host,port,relative,path,directory,file,query,fragment".split(","),u={anchor:"fragment"},p={strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/},
o=/^[0-9]+$/;h.jQuery=function(b){if(b!=null)b.fn.url=function(c){var a="";this.length&&(a=b(this).attr(r(this[0]))||"");return h(a,c)},b.url=h};h.jQuery(window.jQuery);return h});