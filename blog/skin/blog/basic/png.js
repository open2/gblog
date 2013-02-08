function setPNG24(obj) { 
    obj.width=obj.height=1; 
    obj.className=obj.className.replace(/\bPNG24\b/i,''); 
    obj.style.filter = 
    "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');" 
    obj.src=''; 
    return ''; 
} 
