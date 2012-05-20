<?php
class NewsPorlet extends DPortlet
{

  protected function renderContent()
  {
    echo 'News Content';
  }
  
  protected function getTitle()
  {
    return 'News';
  }
  
  protected function getClassName()
  {
    return __CLASS__;
  }
}
