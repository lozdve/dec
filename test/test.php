<?php
    
    
$pdf = pdf_new();
    
    
// 打开一个文件
    
    
pdf_open_file($pdf, "sample.pdf");
    
    
// 开始一个新页面(a4)
    
pdf_begin_page($pdf, 595, 842);
    
// 得到并使用字体对象
    
    
$arial = pdf_findfont($pdf, "arial", "host", 1);
    
    
pdf_setfont($pdf, $arial, 10);
    
// 输出文字 php程序员站
    
pdf_show_xy($pdf, "this is an exam of pdf documents, it is a good lib,",50, 750);
    
    
pdf_show_xy($pdf, "if you like,please try yourself!", 50, 730);
    
    
// 结束一页
    
    
pdf_end_page($pdf);
    
    
// 关闭并保存文件
    
pdf_close($pdf);
?>