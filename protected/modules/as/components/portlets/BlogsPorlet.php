<?php
class BlogsPorlet extends DPortlet
{

  protected function renderContent()
  {
    echo 'Blogs Content';
  }
  
  protected function getTitle()
  {
    return 'Blogs';
  }
  
  protected function getClassName()
  {
    return __CLASS__;
  }
}
