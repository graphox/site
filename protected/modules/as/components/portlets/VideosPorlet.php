<?php
class VideosPorlet extends DPortlet
{

  protected function renderContent()
  {
    echo 'Videos Content';
  }
  
  protected function getTitle()
  {
    return 'Videos';
  }
  
  protected function getClassName()
  {
    return __CLASS__;
  }
}
