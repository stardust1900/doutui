<?php

  define('KEY', '0f2c3faa2019e1212d84befe604da363');
  define('SECRET', '10dea8d0c944b591');
  define('REDIRECT', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'douban_callback.php');

  define('SCOPE', 'douban_basic_common,book_basic_r,book_basic_w,shuo_basic_r,shuo_basic_w');
  define('STATE', 'Something');

  define( "WB_AKEY" , '1079085894' );
  define( "WB_SKEY" , '0c8a7eb3563e94be3317efcac9f0b09a' );
  define( "WB_CALLBACK_URL" , 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . 'weibo_callback.php');

  define( "DOUBAN_PEOPLE_URL" , 'http://www.douban.com/people/' );
