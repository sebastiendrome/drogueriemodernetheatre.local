/*By Eharry.me (https://gist.github.com/Ema4rl/b8ef90be99205ddada5ef2cd6e632ebe)*/
!function(a){"use strict";var b=a("[data-slides]"),c=0,d=b.data("slides"),e=d.length,f=function(){c>=e&&(c=0),b.css("background-image",'url("'+d[c]+'")').show(0,function(){setTimeout(f,5e3)}),c++};f()}(jQuery);