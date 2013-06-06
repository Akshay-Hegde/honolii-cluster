var Cufon=function(){function x(a){var b=this.face=a.face,d={" ":1,"\u00a0":1,"\u3000":1};this.glyphs=a.glyphs;this.w=a.w;this.baseSize=parseInt(b["units-per-em"],10);this.family=b["font-family"].toLowerCase();this.weight=b["font-weight"];this.style=b["font-style"]||"normal";this.viewBox=function(){var c=b.bbox.split(/\s+/),c={minX:parseInt(c[0],10),minY:parseInt(c[1],10),maxX:parseInt(c[2],10),maxY:parseInt(c[3],10)};c.width=c.maxX-c.minX;c.height=c.maxY-c.minY;c.toString=function(){return[this.minX,
this.minY,this.width,this.height].join(" ")};return c}();this.ascent=-parseInt(b.ascent,10);this.descent=-parseInt(b.descent,10);this.height=-this.ascent+this.descent;this.spacing=function(c,a,b){for(var f=this.glyphs,C,n,e=[],A=0,w=-1,g=-1,h;h=c[++w];)if(C=f[h]||this.missingGlyph)n&&(A-=n=n[h]||0,e[g]-=n),A+=e[++g]=~~(C.w||this.w)+a+(d[h]?b:0),n=C.k;e.total=A;return e}}function D(){var a={},b={oblique:"italic",italic:"oblique"};this.add=function(b){(a[b.style]||(a[b.style]={}))[b.weight]=b};this.get=
function(d,c){var t=a[d]||a[b[d]]||a.normal||a.italic||a.oblique;if(!t)return null;c={normal:400,bold:700}[c]||parseInt(c,10);if(t[c])return t[c];var i={1:1,99:0}[c%100],e=[],f,n;i===void 0&&(i=c>400);c==500&&(c=400);for(var g in t)if(t.hasOwnProperty(g)){g=parseInt(g,10);if(!f||g<f)f=g;if(!n||g>n)n=g;e.push(g)}c<f&&(c=f);c>n&&(c=n);e.sort(function(a,b){return(i?a>=c&&b>=c?a<b:a>b:a<=c&&b<=c?a>b:a<b)?-1:1});return t[e[0]]}}function p(a){var b={},d={};this.extend=function(a){for(var d in a)a.hasOwnProperty(d)&&
(b[d]=a[d]);return this};this.get=function(c){return b[c]!=void 0?b[c]:a[c]};this.getSize=function(a,b){return d[a]||(d[a]=new s.Size(this.get(a),b))};this.isUsable=function(){return!!a}}function E(a,b,d){a.addEventListener?a.addEventListener(b,d,!1):a.attachEvent&&a.attachEvent("on"+b,function(){return d.call(a,window.event)})}function z(a,b){var d=r.get(a);if(d.options)return a;b.hover&&b.hoverables[a.nodeName.toLowerCase()]&&o.attach(a);d.options=b;return a}function e(a){var b={};return function(d){b.hasOwnProperty(d)||
(b[d]=a.apply(null,arguments));return b[d]}}function h(a){return document.getElementsByTagName(a)}function j(){for(var a={},b,d,c=0,t=arguments.length;b=arguments[c],c<t;++c)for(d in b)b.hasOwnProperty(d)&&(a[d]=b[d]);return a}function g(a,b,d,c,t,i){var f=document.createDocumentFragment();if(b==="")return f;var e=c.separate,n=b.split(G[e]);if((e=e=="words")&&u)/^\s/.test(b)&&n.unshift(""),/\s$/.test(b)&&n.push("");for(var g=0,h=n.length;g<h;++g)(b=k[c.engine](a,e?s.textAlign(n[g],d,g,h):n[g],d,c,
t,i,g<h-1))&&f.appendChild(b);return f}function l(a,b){var d=a.nodeName.toLowerCase();if(!b.ignore[d]){var d=!b.textless[d],c=s.getStyle(z(a,b)).extend(b),t;a:{t=s.quotedList(c.get("fontFamily").toLowerCase());for(var i,e=0;i=t[e];++e)if(q[i]){t=q[i].get(c.get("fontStyle"),c.get("fontWeight"));break a}t=null}var f,n,h;if(t)for(i=a.firstChild;i;i=f){e=i.nodeType;f=i.nextSibling;if(d&&e==3&&(n?(n.appendData(i.data),a.removeChild(i)):n=i,f))continue;n&&(a.replaceChild(g(t,s.whiteSpace(n.data,c,n,h),
c,b,i,a),n),n=null);if(e==1){if(i.firstChild)if(i.nodeName.toLowerCase()=="cufon")k[b.engine](t,null,c,b,i,a);else arguments.callee(i,b);h=i}}}}var f=function(){return f.replace.apply(null,arguments)},m=f.DOM={ready:function(){var a=!1,b={loaded:1,complete:1},d=[],c=function(){if(!a){a=!0;for(var b;b=d.shift();b());}};document.addEventListener&&(document.addEventListener("DOMContentLoaded",c,!1),window.addEventListener("pageshow",c,!1));!window.opera&&document.readyState&&function(){b[document.readyState]?
c():setTimeout(arguments.callee,10)}();document.readyState&&document.createStyleSheet&&function(){try{document.body.doScroll("left"),c()}catch(a){setTimeout(arguments.callee,1)}}();E(window,"load",c);return function(b){arguments.length?a?b():d.push(b):c()}}(),root:function(){return document.documentElement||document.body}},s=f.CSS={Size:function(a,b){this.value=parseFloat(a);this.unit=String(a).match(/[a-z%]*$/)[0]||"px";this.convert=function(a){return a/b*this.value};this.convertFrom=function(a){return a/
this.value*b};this.toString=function(){return this.value+this.unit}},addClass:function(a,b){var d=a.className;a.className=d+(d&&" ")+b;return a},color:e(function(a){var b={};b.color=a.replace(/^rgba\((.*?),\s*([\d.]+)\)/,function(a,c,e){b.opacity=parseFloat(e);return"rgb("+c+")"});return b}),fontStretch:e(function(a){return typeof a=="number"?a:/%$/.test(a)?parseFloat(a)/100:{"ultra-condensed":0.5,"extra-condensed":0.625,condensed:0.75,"semi-condensed":0.875,"semi-expanded":1.125,expanded:1.25,"extra-expanded":1.5,
"ultra-expanded":2}[a]||1}),getStyle:function(a){var b=document.defaultView;return b&&b.getComputedStyle?new p(b.getComputedStyle(a,null)):a.currentStyle?new p(a.currentStyle):new p(a.style)},gradient:e(function(a){for(var b={id:a,type:a.match(/^-([a-z]+)-gradient\(/)[1],stops:[]},a=a.substr(a.indexOf("(")).match(/([\d.]+=)?(#[a-f0-9]+|[a-z]+\(.*?\)|[a-z]+)/ig),d=0,c=a.length,e;d<c;++d)e=a[d].split("=",2).reverse(),b.stops.push([e[1]||d/(c-1),e[0]]);return b}),quotedList:e(function(a){for(var b=[],
d=/\s*((["'])([\s\S]*?[^\\])\2|[^,]+)\s*/g,c;c=d.exec(a);)b.push(c[3]||c[1]);return b}),recognizesMedia:e(function(a){var b=document.createElement("style"),d;b.type="text/css";b.media=a;try{b.appendChild(document.createTextNode("/**/"))}catch(c){}a=h("head")[0];a.insertBefore(b,a.firstChild);d=(d=b.sheet||b.styleSheet)&&!d.disabled;a.removeChild(b);return d}),removeClass:function(a,b){a.className=a.className.replace(RegExp("(?:^|\\s+)"+b+"(?=\\s|$)","g"),"");return a},supports:function(a,b){var d=
document.createElement("span").style;if(d[a]===void 0)return!1;d[a]=b;return d[a]===b},textAlign:function(a,b,d,c){b.get("textAlign")=="right"?d>0&&(a=" "+a):d<c-1&&(a+=" ");return a},textShadow:e(function(a){if(a=="none")return null;for(var b=[],d={},c,e=0,i=/(#[a-f0-9]+|[a-z]+\(.*?\)|[a-z]+)|(-?[\d.]+[a-z%]*)|,/ig;c=i.exec(a);)c[0]==","?(b.push(d),d={},e=0):c[1]?d.color=c[1]:d[["offX","offY","blur"][e++]]=c[2];b.push(d);return b}),textTransform:function(){var a={uppercase:function(a){return a.toUpperCase()},
lowercase:function(a){return a.toLowerCase()},capitalize:function(a){return a.replace(/\b./g,function(a){return a.toUpperCase()})}};return function(b,d){var c=a[d.get("textTransform")];return c?c(b):b}}(),whiteSpace:function(){var a={inline:1,"inline-block":1,"run-in":1},b=/^\s+/,d=/\s+$/;return function(c,e,i,f){f&&f.nodeName.toLowerCase()=="br"&&(c=c.replace(b,""));if(a[e.get("display")])return c;i.previousSibling||(c=c.replace(b,""));i.nextSibling||(c=c.replace(d,""));return c}}()};s.ready=function(){function a(b,
c){if(!s.recognizesMedia(c||"all"))return!0;if(!b||b.disabled)return!1;try{var d=b.cssRules,e;if(d){var f=0,i=d.length;a:for(;e=d[f],f<i;++f)switch(e.type){case 2:break;case 3:if(!a(e.styleSheet,e.media.mediaText))return!1;break;default:break a}}}catch(g){}return!0}function b(){if(document.createStyleSheet)return!0;var b,c;for(c=0;b=f[c];++c)if(b.rel.toLowerCase()=="stylesheet"&&!b.disabled&&!a(b.sheet,b.media||"screen"))return!1;for(c=0;b=g[c];++c)if(!b.disabled&&!a(b.sheet,b.media||"screen"))return!1;
return!0}var d=!s.recognizesMedia("all"),c=!1,e=[],f=h("link"),g=h("style");m.ready(function(){c||(c=s.getStyle(document.body).isUsable());if(d||c&&b()){d=!0;for(var a;a=e.shift();a());}else setTimeout(arguments.callee,10)});return function(a){d?a():e.push(a)}}();var u=" ".split(/\s+/).length==0,r=new function(){var a={},b=0;this.get=function(d){d=d.cufid||(d.cufid=++b);return a[d]||(a[d]={})}},o=new function(){function a(a){var b=a.relatedTarget;b&&!(this.contains?this.contains(b):this.compareDocumentPosition(b)&
16)&&d(this,a.type=="mouseover")}function b(a){d(this,a.type=="mouseenter")}function d(a,b){setTimeout(function(){var d=r.get(a).options;f.replace(a,b?j(d,d.hover):d,!0)},10)}this.attach=function(c){c.onmouseenter===void 0?(E(c,"mouseover",a),E(c,"mouseout",a)):(E(c,"mouseenter",b),E(c,"mouseleave",b))}},v=new function(){var a=[],b={};this.add=function(d,c){b[d]=a.push(c)-1};this.repeat=function(){var d;if(arguments.length){d=arguments;for(var c=[],e,i=0;e=d[i];++i)c[i]=a[b[e]];d=c}else d=a;for(e=
0;c=d[e++];)f.replace(c[0],c[1],!0)}},K=!1,k={},q={},y={autoDetect:!1,engine:null,forceHitArea:!1,hover:!1,hoverables:{a:!0},ignore:{applet:1,canvas:1,col:1,colgroup:1,head:1,iframe:1,map:1,optgroup:1,option:1,script:1,select:1,style:1,textarea:1,title:1,pre:1},printable:!0,selector:window.Sizzle||window.jQuery&&function(a){return jQuery(a)}||window.dojo&&dojo.query||window.Ext&&Ext.query||window.YAHOO&&YAHOO.util&&YAHOO.util.Selector&&YAHOO.util.Selector.query||window.$$&&function(a){return $$(a)}||
window.$&&function(a){return $(a)}||document.querySelectorAll&&function(a){return document.querySelectorAll(a)}||h,separate:"words",textless:{dl:1,html:1,ol:1,table:1,tbody:1,thead:1,tfoot:1,tr:1,ul:1},textShadow:"none"},G={words:/\s/.test("\u00a0")?/[^\S\u00a0]+/:/\s+/,characters:"",none:/^/};f.now=function(){m.ready();return f};f.refresh=function(){v.repeat.apply(v,arguments);return f};f.registerEngine=function(a,b){if(!b)return f;k[a]=b;return f.set("engine",a)};f.registerFont=function(a){if(!a)return f;
var a=new x(a),b=a.family;q[b]||(q[b]=new D);q[b].add(a);return f.set("fontFamily",'"'+b+'"')};f.replace=function(a,b,d){b=j(y,b);if(!b.engine)return f;K||(s.addClass(m.root(),"cufon-active cufon-loading"),s.ready(function(){s.addClass(s.removeClass(m.root(),"cufon-loading"),"cufon-ready")}),K=!0);if(b.hover)b.forceHitArea=!0;b.autoDetect&&delete b.fontFamily;if(typeof b.textShadow=="string")b.textShadow=s.textShadow(b.textShadow);typeof b.color=="string"&&/^-/.test(b.color)?b.textGradient=s.gradient(b.color):
delete b.textGradient;d||v.add(a,arguments);if(a.nodeType||typeof a=="string")a=[a];s.ready(function(){for(var c=0,d=a.length;c<d;++c){var e=a[c];typeof e=="string"?f.replace(b.selector(e),b,!0):l(e,b)}});return f};f.set=function(a,b){y[a]=b;return f};return f}();
Cufon.registerEngine("vml",function(){function x(e,h){if(h==="0")return 0;if(/px$/i.test(h))return parseFloat(h);var j=e.style.left,g=e.runtimeStyle.left;e.runtimeStyle.left=e.currentStyle.left;e.style.left=h.replace("%","em");var l=e.style.pixelLeft;e.style.left=j;e.runtimeStyle.left=g;return l}function D(e,h,j,g){var l="computed"+g,f=h[l];isNaN(f)&&(f=h.get(g),h[l]=f=f=="normal"?0:~~j.convertFrom(x(e,f)));return f}var p=document.namespaces;if(p&&(p.add("cvml","urn:schemas-microsoft-com:vml"),p=
null,p=document.createElement("cvml:shape"),p.style.behavior="url(#default#VML)",p.coordsize)){var p=null,E=(document.documentMode||0)<8;document.write(('<style type="text/css">cufoncanvas{text-indent:0;}@media screen{cvml\\:shape,cvml\\:rect,cvml\\:fill,cvml\\:shadow{behavior:url(#default#VML);display:block;antialias:true;position:absolute;}cufoncanvas{position:absolute;text-align:left;}cufon{display:inline-block;position:relative;vertical-align:'+(E?"middle":"text-bottom")+";}cufon cufontext{position:absolute;left:-10000in;font-size:1px;}a cufon{cursor:pointer}}@media print{cufon cufoncanvas{display:none;}}</style>").replace(/;/g,
"!important;"));var z={};return function(e,h,j,g,l,f,m){var s=h===null;if(s)h=l.alt;var u=e.viewBox,r;if(!(r=j.computedFontSize)){r=Cufon.CSS.Size;var o;o=j.get("fontSize");o=x(f,/(?:em|ex|%)$|^[a-z-]+$/i.test(o)?"1em":o);r=j.computedFontSize=new r(o+"px",e.baseSize)}o=r;if(s)r=l,l=l.firstChild;else{r=document.createElement("cufon");r.className="cufon cufon-vml";r.alt=h;l=document.createElement("cufoncanvas");r.appendChild(l);if(g.printable){var v=document.createElement("cufontext");v.appendChild(document.createTextNode(h));
r.appendChild(v)}m||r.appendChild(document.createElement("cvml:shape"))}var m=r.style,p=l.style,k=o.convert(u.height),v=Math.ceil(k),k=v/k*Cufon.CSS.fontStretch(j.get("fontStretch")),q=u.minX,y=u.minY;p.height=v;p.top=Math.round(o.convert(y-e.ascent));p.left=Math.round(o.convert(q));m.height=o.convert(e.height)+"px";var G=j.get("color"),a=Cufon.CSS.textTransform(h,j).split(""),b=e.spacing(a,D(f,j,o,"letterSpacing"),D(f,j,o,"wordSpacing"));if(!b.length)return null;var h=b.total,d=-q+h+(u.width-b[b.length-
1]),p=o.convert(d*k),p=Math.round(p),u=d+","+u.height,c,d="r"+u+"ns",t;if(t=g.textGradient){var i=g.textGradient;t=i.id;if(!z[t]){var i=i.stops,B=document.createElement("cvml:fill"),C=[];B.type="gradient";B.angle=180;B.focus="0";B.method="sigma";B.color=i[0][1];for(var n=1,H=i.length-1;n<H;++n)C.push(i[n][0]*100+"% "+i[n][1]);B.colors=C.join(",");B.color2=i[H][1];z[t]=B}t=z[t]}i=e.glyphs;B=0;C=g.textShadow;n=-1;for(H=0;a[++n];){var A=i[a[n]]||e.missingGlyph,w;if(A){if(s)for(w=l.childNodes[H];w.firstChild;)w.removeChild(w.firstChild);
else w=document.createElement("cvml:shape"),l.appendChild(w);w.stroked="f";w.coordsize=u;w.coordorigin=c=q-B+","+y;w.path=(A.d?"m"+A.d+"xe":"")+"m"+c+d;w.fillcolor=G;t&&w.appendChild(t.cloneNode(!1));c=w.style;c.width=p;c.height=v;if(C){c=C[0];var A=C[1],J=Cufon.CSS.color(c.color),I,F=document.createElement("cvml:shadow");F.on="t";F.color=J.color;F.offset=c.offX+","+c.offY;if(A)I=Cufon.CSS.color(A.color),F.type="double",F.color2=I.color,F.offset2=A.offX+","+A.offY;F.opacity=J.opacity||I&&I.opacity||
1;w.appendChild(F)}B+=b[H++]}}e=w.nextSibling;if(g.forceHitArea){if(!e)e=document.createElement("cvml:rect"),e.stroked="f",e.className="cufon-vml-cover",g=document.createElement("cvml:fill"),g.opacity=0,e.appendChild(g),l.appendChild(e);g=e.style;g.width=p;g.height=v}else e&&l.removeChild(e);m.width=Math.max(Math.ceil(o.convert(h*k)),0);if(E){g=j.computedYAdjust;if(g===void 0)g=j.get("lineHeight"),g=="normal"?g="1em":isNaN(g)||(g+="em"),j.computedYAdjust=g=0.5*(x(f,g)-parseFloat(m.height));if(g)m.marginTop=
Math.ceil(g)+"px",m.marginBottom=g+"px"}return r}}}());
Cufon.registerEngine("canvas",function(){var x=document.createElement("canvas");if(x&&x.getContext&&x.getContext.apply){var x=null,D=Cufon.CSS.supports("display","inline-block"),x=!D&&(document.compatMode=="BackCompat"||/frameset|transitional/i.test(document.doctype.publicId)),p=document.createElement("style");p.type="text/css";p.appendChild(document.createTextNode(("cufon{text-indent:0;}@media screen,projection{cufon{display:inline;display:inline-block;position:relative;vertical-align:middle;"+(x?
"":"font-size:1px;line-height:1px;")+"}cufon cufontext{display:-moz-inline-box;display:inline-block;width:0;height:0;overflow:hidden;text-indent:-10000in;}"+(D?"cufon canvas{position:relative;}":"cufon canvas{position:absolute;}")+"}@media print{cufon{padding:0;}cufon canvas{display:none;}}").replace(/;/g,"!important;")));document.getElementsByTagName("head")[0].appendChild(p);return function(p,z,e,h,j){function g(){var b=p.glyphs,e,f=-1,g=-1;for(c.scale(d,1);G[++f];)if(e=b[G[f]]||p.missingGlyph){if(e.d){c.beginPath();
if(e.code){var h=e.code;e=c;for(var j=0,l=h.length;j<l;++j){var k=h[j];e[k.m].apply(e,k.a)}}else{h=e;e="m"+e.d;var j=c,k=l=0,m=[],s=/([mrvxe])([^a-z]*)/g,r=void 0,q=0;a:for(;r=s.exec(e);++q){var o=r[2].split(",");switch(r[1]){case "v":m[q]={m:"bezierCurveTo",a:[l+~~o[0],k+~~o[1],l+~~o[2],k+~~o[3],l+=~~o[4],k+=~~o[5]]};break;case "r":m[q]={m:"lineTo",a:[l+=~~o[0],k+=~~o[1]]};break;case "m":m[q]={m:"moveTo",a:[l=~~o[0],k=~~o[1]]};break;case "x":m[q]={m:"closePath"};break;case "e":break a}j[m[q].m].apply(j,
m[q].a)}h.code=m}c.fill()}c.translate(a[++g],0)}c.restore()}var l=z===null;l&&(z=j.getAttribute("alt"));var f=p.viewBox,m=e.getSize("fontSize",p.baseSize),s=0,u=0,r=0,o=0,v=h.textShadow,x=[];if(v)for(var k=v.length;k--;){var q=v[k],y=m.convertFrom(parseFloat(q.offX)),q=m.convertFrom(parseFloat(q.offY));x[k]=[y,q];q<s&&(s=q);y>u&&(u=y);q>r&&(r=q);y<o&&(o=y)}var G=Cufon.CSS.textTransform(z,e).split(""),a=p.spacing(G,~~m.convertFrom(parseFloat(e.get("letterSpacing"))||0),~~m.convertFrom(parseFloat(e.get("wordSpacing"))||
0));if(!a.length)return null;q=a.total;u+=f.width-a[a.length-1];o+=f.minX;l?(l=j,k=j.firstChild):(l=document.createElement("cufon"),l.className="cufon cufon-canvas",l.setAttribute("alt",z),k=document.createElement("canvas"),l.appendChild(k),h.printable&&(j=document.createElement("cufontext"),j.appendChild(document.createTextNode(z)),l.appendChild(j)));var j=l.style,b=k.style,y=m.convert(f.height),z=Math.ceil(y)/y,d=z*Cufon.CSS.fontStretch(e.get("fontStretch"));q*=d;u=Math.ceil(m.convert(q+u-o));r=
Math.ceil(m.convert(f.height-s+r));k.width=u;k.height=r;b.width=u+"px";b.height=r+"px";s+=f.minY;b.top=Math.round(m.convert(s-p.ascent))+"px";b.left=Math.round(m.convert(o))+"px";r=Math.max(Math.ceil(m.convert(q)),0)+"px";D?(j.width=r,j.height=m.convert(p.height)+"px"):(j.paddingLeft=r,j.paddingBottom=m.convert(p.height)-1+"px");var c=k.getContext("2d"),m=y/f.height;c.scale(m,m*z);c.translate(-o,-s);c.save();if(v)for(k=v.length;k--;)q=v[k],c.save(),c.fillStyle=q.color,c.translate.apply(c,x[k]),g();
if(h=h.textGradient){e=h.stops;f=c.createLinearGradient(0,f.minY,0,f.maxY);k=0;for(h=e.length;k<h;++k)f.addColorStop.apply(f,e[k]);c.fillStyle=f}else c.fillStyle=e.get("color");g();return l}}}());