lastScrollY = 0;
var graySrc = false;
var InterTime = 1;
var maxWidth = -1;
var minWidth = -128;
var numInter = 8;
var BigInter;
var SmallInter;
var o = null;
var i = 0;
online = function(id, _top, _left) {
    var me = id.charAt ? document.getElementById(id) : id, d1 = document.body, d2 = document.documentElement;
    d1.style.height = d2.style.height = '100%'; me.style.top = _top ? _top + 'px' : 0; me.style.right = _left + "px"; 
    me.style.position = 'absolute';
    me.style.display = 'block';
    setInterval(function() { me.style.top = parseInt(me.style.top) + (Math.max(d1.scrollTop, d2.scrollTop) + _top - parseInt(me.style.top)) * 0.1 + 'px'; }, 10 + parseInt(Math.random() * 20));
    return arguments.callee;
};
$(document).ready(function() {
    var html = '';
    html += '<div id="online" style="z-index:2;display:none;" onmouseover=toBig() onmouseout=toSmall()>';
    html += '    <div class="services">';
    html += '        <div class="con">';
    html += '        	<ul>';
    html += ' 				<li class="name">';
    html += '					<p>汕头东庭设计</p>';
    html += '               </li>';
    html += ' 				<li class="phone">';
    html += '				<p>11111111<br>2222222</p>';
    html += '                 </li>';
    html += '            	<li class="qq">';
    html += '                	<p><a href="javascript:;" class="qq"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2012298110&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:2012298110:41" alt="立刻联系我们" title="立刻联系我们"/></a></p>';
    html += '                 </li>';
    html += ' 					<li class="ewm">';
    html += '						<p><img src="images/ewm.png"/></p>';
    html += '               	</li>';
    html += '            </ul>';
    html += '        </div>';
    html += '    </div>';
 
    html += '    <div class="Obtn"></div>';
    html += '</div>';

    $(document.body).append(html);

    o = document.getElementById("online");
    i = parseInt(o.style.right);

    online('online', 210, -128);
});

function Big() {
    if (parseInt(o.style.right) < maxWidth) {
        i = parseInt(o.style.right);
        i += numInter;
        o.style.right = i + "px";
        if (i == maxWidth)
            clearInterval(BigInter);
    }
    if (!graySrc) {
        $(o).find("img").each(function() {
            $(this).attr("src", $(this).attr("Original"));
        });
        graySrc = true;
    }
}
function toBig() {
    clearInterval(SmallInter);
    clearInterval(BigInter);
    BigInter = setInterval(Big, InterTime);
}
function Small() {
    if (parseInt(o.style.right) > minWidth) {
        i = parseInt(o.style.right);
        i -= numInter;
        o.style.right = i + "px";

        if (i == minWidth)
            clearInterval(SmallInter);
    }
}
function toSmall() {
    clearInterval(SmallInter);
    clearInterval(BigInter);
    SmallInter = setInterval(Small, InterTime);

}